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


// ✅ เตรียมคำสั่ง SQL INSERT
$sql = "INSERT INTO page_data_manage_monitor (
        branch_id,
        group_id,
        device_id,
        type_id,
        is_min,
        min_value,
        is_max,
        max_value,
        is_input_digi,
        is_digital_zero,
        is_line,
        is_analog_min_work,
        is_analog_max_work,
        is_digital_work,
        datax_id,
        datax_value,
        is_email,
        is_sms,
        status,
        sort,
        is_condition,
        config_value_1,
        config_value_2,
        config_value_3,
        config_value_4,
        input_line,
        image_banner,
        input_email,
        monitor_name,
        input_sms,
        list_time_of_work,
        createtime,
        updatetime
    ) VALUES (
        $1,$2,$3,$4,$5,$6,$7,$8,$9,$10,
        $11,$12,$13,$14,$15,$16,$17,$18,$19,$20,
        $21,$22,$23,$24,$25,$26,$27,$28,$29,$30,$31,$32,$33
    )
";

$now = date('Y-m-d H:i:s'); // แปลงเป็น string

// ✅ เตรียมค่าพารามิเตอร์
$params = [
    $decode->branch_id ?? null,
    $decode->group_id ?? null,
    $decode->device_id ?? null,
    $decode->type_id ?? null,
    $decode->is_min ?? 0,
    $decode->min_value ?? 0,
    $decode->is_max ?? 0,
    $decode->max_value ?? 0,
    $decode->is_input_digi ?? '0',
    $decode->is_digital_zero ?? '0',
    $decode->is_line ?? '1',
    $decode->is_analog_min_work ?? '0',
    $decode->is_analog_max_work ?? '0',
    $decode->is_digital_work ?? '0',
    $decode->datax_id ?? null,
    $decode->datax_value ?? 0,
    $decode->is_email ?? '1',
    $decode->is_sms ?? '1',
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
    $decode->monitor_name ?? 'Unknown',
    $decode->input_sms ?? '',
    $decode->list_time_of_work ?? '',
    $now,
    $now
];

// ✅ รันคำสั่ง SQL
$result = pg_query_params($db, $sql, $params);


if ($result) {
    echo json_encode(["status" => "success", "message" => "create success"]);
} else {
    echo json_encode(["status" => "error", "message" => pg_last_error($db)]);
}

pg_close($db);
// ✅ ปิดการเชื่อมต่อ
