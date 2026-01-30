<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

// ✅ ใช้ตัวแปรเดียวกันกับที่เชื่อมต่อ
$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

// ✅ รับค่าจาก Flutter หรือ Postman
$json = $_POST['json'] ?? '';
// $json = file_get_contents("php://input");
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
if (empty($json)) {
    echo json_encode(["status" => "error", "message" => "need parameter json"]);
    pg_close($db);
    exit;
}


$decode = json_decode($json);

// ✅ ตรวจสอบว่ามี monitor_id หรือไม่
if (empty($decode->monitor_id)) {
    echo json_encode(["status" => "error", "message" => "missing monitor_id"]);
    pg_close($db);
    exit;
}
pg_query($db, "BEGIN");

try {

    // ✅ เตรียมคำสั่ง SQL UPDATE
    $sql = "UPDATE page_data_manage_monitor SET 
        branch_id = $1,
        group_id = $2,
        device_id = $3,
        type_id = $4,
        is_min = $5,
        min_value = $6,
        is_max = $7,
        max_value = $8,
        is_input_digi = $9,
        is_digital_zero = $10,
        is_line = $11,
        is_analog_min_work = $12,
        is_analog_max_work = $13,
        is_digital_work = $14,
        datax_id = $15,
        datax_value = $16,
        is_email = $17,
        is_sms = $18,
        updatetime = NOW(),
        status = $19,
        sort = $20,
        is_condition = $21,
        config_value_1 = $22,
        config_value_2 = $23,
        config_value_3 = $24,
        config_value_4 = $25,
        input_line = $26,
        image_banner = $27,
        input_email = $28,
        monitor_name = $29,
        input_sms = $30,
        list_time_of_work = $31
    WHERE monitor_id = $32
";

    // ✅ เตรียมค่าพารามิเตอร์
    $params = [
        $decode->branch_id ?? 0,
        $decode->group_id ?? 0,
        $decode->device_id ?? 0,
        $decode->type_id ?? 0,
        $decode->is_min ?? 0,
        $decode->min_value ?? 0,
        $decode->is_max ?? 0,
        $decode->max_value ?? 0,
        $decode->is_input_digi ?? '0',
        $decode->is_digital_zero ?? '0',
        $decode->is_line ?? '0',
        $decode->is_analog_min_work ?? '0',
        $decode->is_analog_max_work ?? '0',
        $decode->is_digital_work ?? '0',
        $decode->datax_id ?? 0,
        $decode->datax_value ?? 0,
        $decode->is_email ?? '0',
        $decode->is_sms ?? '0',
        $decode->status ?? '1',
        $decode->sort ?? 0,
        $decode->is_condition ?? '0',
        $decode->config_value_1 ?? 0,
        $decode->config_value_2 ?? 0,
        $decode->config_value_3 ?? 0,
        $decode->config_value_4 ?? 0,
        $decode->input_line ?? '',
        $decode->image_banner ?? null,
        $decode->input_email ?? '',
        $decode->monitor_name ?? '',
        $decode->input_sms ?? null,
        $decode->list_time_of_work ?? '',
        $decode->monitor_id
    ];

    // ✅ รันคำสั่ง SQL
    $result = pg_query_params($db, $sql, $params);
    if (!$result) {
        throw new Exception(pg_last_error($db));
    }

    if (!empty($decode->list_time) && is_array($decode->list_time)) {

        foreach ($decode->list_time as $t) {

            $id = intval($t->id ?? 0);
            $start = $t->start_work ?? null;
            $end = $t->end_work ?? null;
            $isDelete = intval($t->is_delete ?? 0);

            // 🔴 ลบ
            if ($id > 0 && $isDelete === 1) {
                pg_query_params(
                    $db,
                    "DELETE FROM page_data_manage_monitor_sub WHERE monitor_sub_id = $1 AND monitor_id = $2",
                    [$id, $decode->monitor_id]
                );
                continue;
            }

            // 🟢 เพิ่มใหม่
            if ($id === 0 && $isDelete === 0) {
                pg_query_params(
                    $db,
                    "INSERT INTO page_data_manage_monitor_sub
                 (monitor_id, start_work, end_work, status, createtime, updatetime)
                 VALUES ($1, $2, $3, 1, NOW(), NOW())",
                    [$decode->monitor_id, $start, $end]
                );
                continue;
            }

            // 🟡 แก้ไข
            if ($id > 0 && $isDelete === 0) {
                pg_query_params(
                    $db,
                    "UPDATE page_data_manage_monitor_sub
                 SET start_work = $1,
                     end_work = $2,
                     updatetime = NOW()
                 WHERE monitor_sub_id = $3 AND monitor_id = $4",
                    [$start, $end, $id, $decode->monitor_id]
                );
            }
        }
    }

    pg_query($db, "COMMIT");
    echo json_encode(["status" => "success"]);
} catch (Exception $e) {
    pg_query($db, "ROLLBACK");
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}


pg_close($db);
// ✅ ปิดการเชื่อมต่อ
