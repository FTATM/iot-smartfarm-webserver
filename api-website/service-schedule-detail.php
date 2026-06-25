<?php
header('Content-Type: application/json');

$date = $_GET['date'] ?? '';
$dt = DateTime::createFromFormat('Y-m-d', $date);

if (
    !$dt ||
    $dt->format('Y-m-d') !== $date
) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid date format. Expected YYYY-MM-DD'
    ]);
    exit;
}

$monitorFile = __DIR__ . "/../services/logs/monitor_execute_{$date}.json";

$monitorData = [];

if (file_exists($monitorFile)) {
    $monitorData = json_decode(file_get_contents($monitorFile), true);
}

echo json_encode($monitorData, JSON_PRETTY_PRINT);
