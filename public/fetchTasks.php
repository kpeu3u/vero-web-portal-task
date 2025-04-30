<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Service\TaskCollector;

header('Content-Type: application/json');

try {
    $tasks = new TaskCollector();
    $taskData = $tasks->fetchTasks();
    echo json_encode($taskData);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
