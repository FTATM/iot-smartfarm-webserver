<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");

// รองรับ Preflight request ของ Browser
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['keys']) || !is_array($input['keys'])) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Whitelist
|--------------------------------------------------------------------------
*/

$result = [];

foreach ($input['keys'] as $key) {

    if (!in_array($key, ENV_PUBLIC_KEYS, true)) {
        continue;
    }

    $result[$key] = getEnvValue($key);

}

echo json_encode([
    'success' => true,
    'data' => $result
]);