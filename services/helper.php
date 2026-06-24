<?php
function updateScriptStatus(
    string $key,
    bool $running,
    string $message = '',
    ?int $progress = null
): void {

    $file = __DIR__ . '/statusall/' . $key . '.json';

    if (!is_dir(dirname($file))) {
        mkdir(dirname($file), 0777, true);
    }

    $status = [];

    if (file_exists($file)) {
        $status = json_decode(
            file_get_contents($file),
            true
        ) ?? [];
    }

    $status['running'] = $running;
    $status['message'] = $message;

    if ($progress !== null) {
        $status['progress'] = $progress;
    }

    if ($running) {

        if (empty($status['started_at'])) {
            $status['started_at'] =
                date('Y-m-d H:i:s');
        }

        $status['finished_at'] = null;
    } else {

        $status['finished_at'] =
            date('Y-m-d H:i:s');

        if (!isset($status['progress'])) {
            $status['progress'] = 100;
        }
    }

    $status['updated_at'] =
        date('Y-m-d H:i:s');

    $status['heartbeat'] =
        time();

    file_put_contents(
        $file,
        json_encode(
            $status,
            JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE
        ),
        LOCK_EX
    );
}


//get status by key
function getScriptStatus(string $key): array
{
    $file = __DIR__ . '/statusall/' . $key . '.json';

    if (!file_exists($file)) {

        return [
            'running' => false,
            'message' => 'Status file not found'
        ];
    }

    $status = json_decode(
        file_get_contents($file),
        true
    );

    if (!is_array($status)) {

        return [
            'running' => false,
            'message' => 'Invalid status file'
        ];
    }

    return $status;
}

function resetScriptStatus(string $key): array
{
    $file = __DIR__ . '/statusall/' . $key . '.json';

    if (!file_exists($file)) {

        return [
            'success' => false,
            'message' => 'Status file not found'
        ];
    }

    $status = [
        "running" => false,
        "progress" => 0,
        "message" => "",
        "heartbeat" => null,
        "started_at" => null,
        "updated_at" => null,
        "finished_at" => null
    ];

    file_put_contents(
        $file,
        json_encode(
            $status,
            JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE
        ),
        LOCK_EX
    );

    return [
        'success' => true,
        'message' => 'Reset success'
    ];
}

function isScriptAlive(
    string $key,
    int $timeout = 180
): bool {

    $status = getScriptStatus($key);

    if (!($status['running'] ?? false)) {
        return false;
    }

    $heartbeat = $status['heartbeat'] ?? 0;

    return (time() - $heartbeat) <= $timeout;
}

/*
|--------------------------------------------------------------------------
| Windows Service
|--------------------------------------------------------------------------
*/
function serviceBat(
    string $action,
    string $service = 'all'
): string {

    $bat = __DIR__ . DIRECTORY_SEPARATOR . 'service_.bat';

    if (!file_exists($bat)) {
        return 'service_.bat not found';
    }

    return shell_exec(
        sprintf(
            'cmd /c %s %s %s',
            $bat,
            $action,
            $service
        )
    ) ?? '';
}

function getServiceStatus(string $service): array
{
    $output = serviceBat(
        'status',
        $service
    );

    return [
        'running' =>
        stripos($output, 'RUNNING') !== false,

        'raw' => $output
    ];
}
