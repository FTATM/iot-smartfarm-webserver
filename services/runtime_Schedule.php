<?php

declare(strict_types=1);

set_time_limit(0);
date_default_timezone_set('Asia/Bangkok');

require __DIR__ . '/helper.php';

if (php_sapi_name() !== 'cli') {
    die("This script must be run from CLI." . PHP_EOL);
}

/*
|--------------------------------------------------------------------------
| Last Run History
|--------------------------------------------------------------------------
*/

$lastRunFile = __DIR__ . '/logs/schedule-runtime_history_' . date('Y-m-d') . '.json';

if (!is_dir(dirname($lastRunFile))) {
    mkdir(dirname($lastRunFile), 0777, true);
}

$lastRun = [];

if (file_exists($lastRunFile)) {

    $json = file_get_contents($lastRunFile);

    $lastRun = json_decode($json, true);

    if (!is_array($lastRun)) {
        $lastRun = [];
    }
}

/*
|--------------------------------------------------------------------------
| Shutdown / Signal Handler
|--------------------------------------------------------------------------
*/

$scriptStopped = false;

$shutdown = function (?int $signal = null) use (&$scriptStopped) {

    if ($scriptStopped) {
        return;
    }

    $scriptStopped = true;

    $message = match ($signal) {
        SIGINT  => 'Close by Ctrl+C',
        SIGTERM => 'Terminate signal',
        SIGHUP  => 'Terminal closed',
        default => 'Script closed',
    };

    updateScriptStatus(
        'schedule-runtime',
        false,
        $message
    );

    echo PHP_EOL;
    echo '[' . date('Y-m-d H:i:s') . "] {$message}" . PHP_EOL;

    exit(0);
};

/*
|--------------------------------------------------------------------------
| Signal
|--------------------------------------------------------------------------
*/

if (extension_loaded('pcntl')) {

    pcntl_async_signals(true);

    pcntl_signal(SIGINT, $shutdown);    // Ctrl+C
    pcntl_signal(SIGTERM, $shutdown);   // kill
    pcntl_signal(SIGHUP, $shutdown);    // terminal closed
}

/*
|--------------------------------------------------------------------------
| Fatal Error / exit()
|--------------------------------------------------------------------------
*/

register_shutdown_function(function () use (&$scriptStopped) {

    if ($scriptStopped) {
        return;
    }

    $error = error_get_last();

    if ($error !== null) {

        updateScriptStatus(
            'schedule-runtime',
            false,
            'Fatal Error : ' . $error['message']
        );
    } else {

        updateScriptStatus(
            'schedule-runtime',
            false,
            'Script stopped'
        );
    }
});

/*
|--------------------------------------------------------------------------
| Start
|--------------------------------------------------------------------------
*/

updateScriptStatus(
    'schedule-runtime',
    true,
    'เริ่มการทำงาน'
);

echo '[' . date('Y-m-d H:i:s') . "] Schedule Runtime Started" . PHP_EOL;

/*
|--------------------------------------------------------------------------
| Main Loop
|--------------------------------------------------------------------------
*/

try {

    while (true) {

        updateScriptStatus(
            'schedule-runtime',
            true,
            'Waiting next schedule (' . date('Y-m-d H:i:s') . ')'
        );

        $nowMinute  = date('Y-m-d H:i');
        $currentTime = date('H:i');

        $time = $currentTime;

        // foreach ($times as $time) {

        if (
            $currentTime === $time &&
            ($lastRun[$time] ?? '') !== $nowMinute
        ) {
            // if (true) {

            try {

                updateScriptStatus(
                    'schedule-runtime',
                    true,
                    "กำลังรัน Schedule.php เวลา {$time}"
                );

                echo '[' . date('Y-m-d H:i:s') .
                    "] Schedule Running ({$time})" . PHP_EOL;

                /*
                    |--------------------------------------------------------------------------
                    | Run Schedule
                    |--------------------------------------------------------------------------
                    */

                $status = getScriptStatus('schedule');

                if (!($status['running'] ?? false)) {

                    $php = 'C:\xampp\php\php.exe';
                    $script = __DIR__ . "\schedule.php";

                    exec(
                        '"' . $php . '" -dxdebug.mode=off "' . $script . '" > NUL 2>&1 &'
                    );

                    echo "Spawn Schedule" . PHP_EOL;
                } else {

                    echo "Schedule is already running" . PHP_EOL;
                }
                /*
                    |--------------------------------------------------------------------------
                    | Save History
                    |--------------------------------------------------------------------------
                    */

                $lastRun[$time] = $nowMinute;

                file_put_contents(
                    $lastRunFile,
                    json_encode(
                        $lastRun,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                    )
                );

                updateScriptStatus(
                    'schedule-runtime',
                    true,
                    "Schedule เสร็จสิ้น ({$time})"
                );

                echo '[' . date('Y-m-d H:i:s') .
                    "] Schedule Finished ({$time})" . PHP_EOL;
            } catch (Throwable $e) {

                updateScriptStatus(
                    'schedule-runtime',
                    true,
                    'Schedule Error : ' . $e->getMessage()
                );

                echo '[' . date('Y-m-d H:i:s') .
                    '] Schedule Error : ' .
                    $e->getMessage() . PHP_EOL;
            }
        }
        // }

        sleep(60);
    }
} catch (Throwable $e) {

    updateScriptStatus(
        'schedule-runtime',
        false,
        'Runtime Error : ' . $e->getMessage()
    );

    echo '[' . date('Y-m-d H:i:s') .
        '] Runtime Error : ' .
        $e->getMessage() . PHP_EOL;

    exit(1);
}
