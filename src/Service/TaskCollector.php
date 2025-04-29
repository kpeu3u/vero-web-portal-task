<?php

declare(strict_types=1);

namespace App\Service;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TaskCollector
{
    private Client $httpClient;
    private const API_LOGIN_URL = '/index.php/login';
    private const API_TASKS_URL = '/dev/index.php/v1/tasks/select';

    private readonly string $apiBaseUrl;
    private readonly string $apiUsername;
    private readonly string $apiPassword;
    private readonly string $apiBasicAuth;


    public function __construct()
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Required environment variables
        $dotenv->required([
            'API_BASE_URL',
            'API_USERNAME',
            'API_PASSWORD',
            'API_BASIC_AUTH',
        ]);

        // Initialize properties from environment variables
        $this->apiBaseUrl = $_ENV['API_BASE_URL'];
        $this->apiUsername = $_ENV['API_USERNAME'];
        $this->apiPassword = $_ENV['API_PASSWORD'];
        $this->apiBasicAuth = $_ENV['API_BASIC_AUTH'];

        $this->httpClient = new Client([
            'base_uri' => $this->apiBaseUrl,
        ]);
    }


    public function fetchTasks(): array
    {
        try {
            // First, get the authentication token
            $loginResponse = $this->httpClient->post(self::API_LOGIN_URL, [
                'headers' => [
                    'Authorization' => 'Basic '. $this->apiBasicAuth,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'username' => $this->apiUsername,
                    'password' => $this->apiPassword,
                ]
            ]);

            $loginData = json_decode($loginResponse->getBody()->getContents(), true);

            // Extract the access token from the login response
            $accessToken = $loginData['oauth']['access_token'] ?? null;

            if (!$accessToken) {
                throw new \RuntimeException('Failed to get access token');
            }

            // Use the access token to fetch tasks
            $tasksResponse = $this->httpClient->get(self::API_TASKS_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]
            ]);

            return json_decode($tasksResponse->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            echo 'Failed to fetch tasks. Error: (' . $e->getMessage() . ') ' . PHP_EOL;
            return [];
        }
    }


}
