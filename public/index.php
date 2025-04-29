<?php
declare(strict_types=1);


require __DIR__ . '/../vendor/autoload.php';

use App\Service\TaskCollector;

$tasks = new TaskCollector();


try {
    $taskData = $tasks->fetchTasks();
    echo '<pre>';
    print_r($taskData);
    echo '</pre>';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}