<?php
require_once(__DIR__ . "/queue.php");

// register
Flight::route('GET /queue/dashboard', function () {
    $queue_class = new Queue();
    $queue_class->get_dashboard();
});
