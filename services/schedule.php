<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
date_default_timezone_set('Asia/Bangkok');
include_once(__DIR__ . '/../includes/fn/pg_connect.php');
require_once __DIR__ . '/helper.php';
updateScriptStatus('schedule', true, 'เริ่มการทำงาน');

try {
    // ================================ Update status ======================================

    updateScriptStatus('schedule', true, 'กำลังเชื่อมต่อฐานข้อมูล', 3);

    // ================================ Connect Database ======================================

    $db = pg_connect("$host $port $dbname $credentials");

    if (!$db) {
        http_response_code(500);

        echo json_encode([
            "status" => "error",
            "message" => "can not connect to database"
        ]);
        updateScriptStatus('schedule', false, 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้', 5);
        exit;
    }
    // ========================================================================================


    // ================================ Log Setting ===========================================
    $logDir = __DIR__ . '/logs';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    $logFile = $logDir . '/monitor_execute_' . date('Ymd') . '.log';

    file_put_contents(
        $logFile,
        "\n============================================================\n" .
            "RUN : " . date('Y-m-d H:i:s') . "\n" .
            "============================================================\n\n",
        FILE_APPEND
    );
    // ========================================================================================


    // ================================ Get Branch ============================================
    $sql_brch = "
SELECT branch_id, branch_name
FROM branch_info
ORDER BY branch_id ASC
";
    updateScriptStatus('schedule', true, 'กำลังดึงข้อมูล branch', 21);

    $result = pg_query($db, $sql_brch);

    if (!$result) {

        $msg =
            "SYSTEM ERROR\n" .
            "Message : Not found branch in database\n\n";

        file_put_contents($logFile, $msg, FILE_APPEND);

        http_response_code(401);

        echo json_encode([
            "status" => "error",
            "message" => "Not found branch in database"
        ]);
        updateScriptStatus('schedule', false, 'ไม่สามารถดึงข้อมูลจากฐานข้อมูลได้ โปรดเช็ค logs.', 25);

        exit;
    }
    // ========================================================================================


    while ($row = pg_fetch_assoc($result)) {

        $branch_id   = (int)($row['branch_id'] ?? 0);
        $branch_name = $row['branch_name'] ?? '';

        if ($branch_id === 0) {

            $msg =
                "SYSTEM ERROR\n" .
                "Message : branch_id is null\n\n";

            file_put_contents($logFile, $msg, FILE_APPEND);
            updateScriptStatus('schedule', false, 'เกิดข้อผิดพลาด มีข้อมูลเสียหาย โปรดติดต่อแอดมิน.', 31);

            continue;
        }


        // ================================ Get Monitor =======================================
        $sql_monitor = "
    SELECT
        m.monitor_id,
        m.target_id,
        m.datax_value,
        m.is_min,
        m.is_max,
        m.min_value,
        m.max_value,
        m.is_line,
        m.is_email,
        m.is_sms,
        m.input_line,
        m.input_email,
        m.input_sms,
        m.list_time_of_work,

        (
            SELECT json_agg(
                json_build_object(
                    'start_work', start_work,
                    'end_work', end_work
                )
                ORDER BY start_work
            )
            FROM page_data_manage_monitor_sub s
            WHERE s.monitor_id = m.monitor_id
        ) AS list_time

    FROM page_data_manage_monitor m
    WHERE m.branch_id = $1
        AND NULLIF(TRIM(m.list_time_of_work), '') IS NOT NULL
    ";

        updateScriptStatus('schedule', true, 'กำลังดึงข้อมูลอุปกรณ์จากฐานข้อมูล', 33);
        $result_m = pg_query_params($db, $sql_monitor, [$branch_id]);

        if (!$result_m) {

            $msg =
                "Branch[{$branch_id}] {$branch_name}\n" .
                "Result  : ERROR\n" .
                "Message : " . pg_last_error($db) . "\n\n" .
                "------------------------------------------------------------\n\n";

            file_put_contents($logFile, $msg, FILE_APPEND);
            updateScriptStatus('schedule', false, 'เกิดข้อผิดพลาดไม่ทราบสาเหตุ โปรดติดต่อแอดมิน.', 35);
            continue;
        }


        // ================================ Calculate =========================================
        $monitors = [];

        updateScriptStatus('schedule', true, 'กำลังเรียบเรียงข้อมูล', 42);
        while ($row_m = pg_fetch_assoc($result_m)) {

            $isWorkingNow = 0;

            // Monday=0 ... Sunday=6
            $currentDay = date('N') - 1;

            $workDays = [];

            if (!empty($row_m['list_time_of_work'])) {
                $workDays = array_map(
                    'intval',
                    explode(',', $row_m['list_time_of_work'])
                );
            }

            if (in_array($currentDay, $workDays)) {

                $currentTime = date('H:i:s');

                $timeRanges = json_decode($row_m['list_time'], true);

                if (is_array($timeRanges)) {

                    foreach ($timeRanges as $timeRange) {

                        $start = $timeRange['start_work'];
                        $end   = $timeRange['end_work'];

                        // รองรับกะข้ามวัน
                        if ($start <= $end) {

                            $matched =
                                ($currentTime >= $start &&
                                    $currentTime <= $end);
                        } else {

                            $matched =
                                ($currentTime >= $start ||
                                    $currentTime <= $end);
                        }

                        if ($matched) {
                            $isWorkingNow = 1;

                            if ($row_m['is_min'] == 1 && $row_m['datax_value'] <= $row_m['min_value']) {
                                $isWorkingNow = 0;
                            }
                            if ($row_m['is_max'] == 1 && $row_m['datax_value'] >= $row_m['max_value']) {
                                $isWorkingNow = 0;
                            }
                            break;
                        }
                    }
                }
            }

            $monitors[] = [
                "monitor_id" => (int)$row_m['target_id'],
                "data_value" => $isWorkingNow
            ];
        }


        // ================================ Skip ==============================================
        if (empty($monitors)) {

            $msg =
                "Branch[{$branch_id}] {$branch_name}\n" .
                "Result  : SKIP\n" .
                "Reason  : not has monitor open notify or schedule\n\n" .
                "------------------------------------------------------------\n\n";

            file_put_contents($logFile, $msg, FILE_APPEND);

            updateScriptStatus('schedule', false, 'ไม่พบอุปกรณ์ที่ตั้งการทำงานล่วงหน้า', 54);
            continue;
        }


        updateScriptStatus('schedule', true, 'กำลังอัพเดตข้อมูล', 67);
        // ================================ Call API ==========================================
        $json = json_encode($monitors, JSON_UNESCAPED_UNICODE);

        $ch = curl_init(
            'http://localhost/iotsf/api_push_data_by_id_multidata.php'
        );

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json)
            ],
            CURLOPT_POSTFIELDS => $json,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        // ================================ CURL ERROR ========================================
        if ($response === false) {

            $error = curl_error($ch);

            $msg =
                "Branch[{$branch_id}] {$branch_name}\n" .
                "Payload : {$json}\n" .
                "Result  : ERROR\n" .
                "Message : {$error}\n\n" .
                "------------------------------------------------------------\n\n";

            file_put_contents($logFile, $msg, FILE_APPEND);

            updateScriptStatus('schedule', false, 'เกิดข้อผิดพลาดในการอัพเดตข้อมูลปัจจุบัน', 73);
        } else {

            $status = ($httpCode == 200) ? 'SUCCESS' : 'FAILED';

            $msg =
                "Branch[{$branch_id}] {$branch_name}\n" .
                "Payload : {$json}\n" .
                "HTTP    : {$httpCode}\n" .
                "Result  : {$status}\n" .
                "Response:\n{$response}\n\n" .
                "------------------------------------------------------------\n\n";

            file_put_contents($logFile, $msg, FILE_APPEND);
            updateScriptStatus('schedule', true, 'อัพเดตข้อมูลสำเร็จ กำลังทำการปิดการเชื่อมต่อ', 84);
        }

        curl_close($ch);
    }


    // ================================ End Log ===============================================
    file_put_contents(
        $logFile,
        "============================================================\n" .
            "END RUN\n" .
            "============================================================\n\n",
        FILE_APPEND
    );
    // ========================================================================================


    // ================================ Disconnect Database ===================================
    pg_close($db);
    // ========================================================================================
    updateScriptStatus('schedule', true, 'ปิดการเชื่อมต่อเรียบร้อย', 90);



    echo json_encode([
        "status" => "success",
        "message" => "process completed"
    ], JSON_UNESCAPED_UNICODE);

    // เสร็จงาน
    updateScriptStatus('schedule', false, 'เสร็จสิ้น', 100);
} catch (Exception $e) {

    updateScriptStatus('schedule', false, 'เกิดข้อผิดพลาด : ' . $e->getMessage());
}
