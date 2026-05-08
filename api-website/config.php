<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$AI_MODE = (int)($_ENV['AI_MODE'] ?? 0);    

$AI_config = [
    'model' => $_ENV['AI_MODEL'],
    'api_url' => $_ENV['AI_API_URL']
];

$AI_EXTERNAL_config = [
    'model' => $_ENV['AI_EXTERNAL_MODEL'],
    'api_url' => $_ENV['AI_EXTERNAL_URL'],
    'api_key' => $_ENV['AI_EXTERNAL_KEY']
];

$TMD_config = [
    'api_url' => $_ENV['TMD_API_URL'],
    'token'   => $_ENV['TMD_API_KEY'],
    'rain_cond_threshold' => $_ENV['RAIN_THRESHOLD']
];
