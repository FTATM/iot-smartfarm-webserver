<?php

header('Content-Type: application/json');

require_once 'config.php';

$input = json_decode(file_get_contents('php://input'), true);

$key   = $input['key'] ?? '';
$value = $input['value'] ?? '';

/*
|--------------------------------------------------------------------------
| Whitelist
|--------------------------------------------------------------------------
*/

if (!in_array($key, ENV_PUBLIC_KEYS, true)) {

    http_response_code(403);

    echo json_encode([
        'success' => false,
        'message' => 'Permission denied.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if ($key === 'AI_MODE' && !in_array($value, ['0', '1'], true)) {

    echo json_encode([
        'success' => false,
        'message' => 'AI_MODE must be 0 or 1.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Update
|--------------------------------------------------------------------------
*/

if (updateEnvValue($key, $value)) {

    echo json_encode([
        'success' => true
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Cannot update .env'
    ]);

}