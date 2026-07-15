<?php

header('Content-Type: application/json');

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