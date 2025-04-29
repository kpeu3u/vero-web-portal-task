<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\TaskCollector;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;

class TaskCollectorTest extends TestCase
{
    private TaskCollector $taskCollector;
    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        // Create environment variables required for TaskCollector
        putenv('API_BASE_URL=https://example.com');
        putenv('API_USERNAME=test_user');
        putenv('API_PASSWORD=test_password');
        putenv('API_BASIC_AUTH=test_basic_auth');

        // Create a mock handler
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        
        // Create the TaskCollector instance
        $this->taskCollector = new TaskCollector();
        
        // Replace the HTTP client with our mocked version
        $client = new Client(['handler' => $handlerStack]);
        $reflection = new ReflectionClass($this->taskCollector);
        $property = $reflection->getProperty('httpClient');
        $property->setValue($this->taskCollector, $client);
    }

    public function testSuccessfulTaskFetch(): void
    {
        // Mock successful login response
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'oauth' => [
                    'access_token' => 'test_token'
                ]
            ]))
        );

        // Mock successful tasks response
        $expectedTasks = ['tasks' => [
            ['id' => 1, 'title' => 'Task 1'],
            ['id' => 2, 'title' => 'Task 2']
        ]];
        $this->mockHandler->append(
            new Response(200, [], json_encode($expectedTasks))
        );

        $result = $this->taskCollector->fetchTasks();

        $this->assertEquals($expectedTasks, $result);
    }

    public function testFailedLogin(): void
    {
        // Mock failed login response
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'oauth' => []
            ]))
        );
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to get access token');

        $result = $this->taskCollector->fetchTasks();

        $this->assertEmpty($result);
    }

    public function testGuzzleException(): void
    {
        // Test connection error
        $this->mockHandler->append(
            new ConnectException(
                "Failed to connect",
                new Request('POST', '/index.php/login')
            )
        );

        ob_start();
        $result = $this->taskCollector->fetchTasks();
        $output = ob_get_clean();

        $this->assertEmpty($result);
        $this->assertStringContainsString('Failed to fetch tasks', $output);
        $this->assertStringContainsString('Failed to connect', $output);


    }

    protected function tearDown(): void
    {
        // Clean up environment variables
        putenv('API_BASE_URL');
        putenv('API_USERNAME');
        putenv('API_PASSWORD');
        putenv('API_BASIC_AUTH');
    }
}