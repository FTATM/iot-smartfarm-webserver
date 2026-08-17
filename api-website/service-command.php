<?php

require_once __DIR__ . '/../services/helper.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");

// รองรับ Preflight request ของ Browser
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$action  = $_POST['action']  ?? '';
$service = $_POST['service'] ?? '';

switch ($action) {

    case 'dashboard':

        echo json_encode([

            'runtime_schedule' => [

                'service' =>
                    getServiceStatus(
                        'runtime_schedule'
                    ),

                'script_running' =>
                    isScriptAlive(
                        'runtime_schedule'
                    ),

                'status' =>
                    getScriptStatus(
                        'runtime_schedule'
                    )
            ],

            'runtime_ai' => [

                'service' =>
                    getServiceStatus(
                        'runtime_ai'
                    ),

                'script_running' =>
                    isScriptAlive(
                        'runtime_ai'
                    ),

                'status' =>
                    getScriptStatus(
                        'runtime_ai'
                    )
            ]
        ]);

        exit;

    case 'start':

        echo json_encode([
            'success' => true,
            'output' => serviceBat(
                'start',
                $service
            )
        ]);

        exit;

    case 'stop':

        echo json_encode([
            'success' => true,
            'output' => serviceBat(
                'stop',
                $service
            )
        ]);

        exit;

    case 'restart':

        echo json_encode([
            'success' => true,
            'output' => serviceBat(
                'restart',
                $service
            )
        ]);

        exit;

    case 'status':

        echo json_encode(
            getServiceStatus(
                $service
            )
        );

        exit;
}

echo json_encode([
    'success' => false,
    'message' => 'Invalid action'
]);