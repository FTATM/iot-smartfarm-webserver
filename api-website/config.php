<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$AI_config = [
    'model' => $_ENV['AI_MODEL'],
    'api_url' => $_ENV['AI_API_URL']
];


