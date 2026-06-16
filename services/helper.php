<?php
function updateScriptStatus(
    string $key,
    bool $running,
    string $message = '',
    ?int $progress = null
): void {

    $file = __DIR__ . '/status.json';

    // โหลดข้อมูลเดิม
    $allStatus = [];

    if (file_exists($file)) {
        $allStatus = json_decode(file_get_contents($file), true) ?? [];
    }

    // ถ้า key นี้ยังไม่มี และกำลังเริ่มทำงาน
    if (!isset($allStatus[$key])) {
        $allStatus[$key] = [
            'started_at' => null,
            'finished_at' => null
        ];
    }

    // update เฉพาะ key ที่ต้องการ
    $allStatus[$key]['running'] = $running;
    $allStatus[$key]['message'] = $message;

    if ($progress !== null) {
        $allStatus[$key]['progress'] = $progress;
    }

    if ($running) {
        // เริ่มงานครั้งแรก
        if (empty($allStatus[$key]['started_at'])) {
            $allStatus[$key]['started_at'] = date('Y-m-d H:i:s');
        }

        $allStatus[$key]['finished_at'] = null;
    } else {
        // จบงาน
        $allStatus[$key]['finished_at'] = date('Y-m-d H:i:s');

        if (!isset($allStatus[$key]['progress'])) {
            $allStatus[$key]['progress'] = 100;
        }
    }
    // อัพเดตเวลา
    $allStatus[$key]['updated_at'] = date('Y-m-d H:i:s');

    file_put_contents(
        $file,
        json_encode(
            $allStatus,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ),
        LOCK_EX
    );
}


//get status by key
function getScriptStatus(string $key): array
{
    $file = __DIR__ . '/status.json';

    if (!file_exists($file)) {
        return [
            'running' => false,
            'message' => 'Status file not found',
        ];
    }

    $allStatus = json_decode(file_get_contents($file), true);

    if (!is_array($allStatus)) {
        return [
            'running' => false,
            'message' => 'Invalid status file',
        ];
    }

    return $allStatus[$key] ?? [
        'running' => false,
        'message' => 'Status not found',
    ];
}
