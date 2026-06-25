<?php
header('Content-Type: application/json');

$folder = __DIR__ . '/../services/logs/schedule-*.json';

$result = [];
foreach (glob($folder) as $file) {
    $result[] = [
        'name' => basename($file),
        'path' => $file,
        'size' => filesize($file),
        'modified' => filemtime($file),
    ];
}

echo json_encode($result, JSON_PRETTY_PRINT);
