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
$branch_id = $_POST['bid'] ?? '';
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
if (empty($branch_id)) {
    echo json_encode(["status" => "error", "message" => "please input branch id"]);
    pg_close($db);
    exit;
}

// // ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)
$sql = "SELECT * FROM page_data_manage_monitor
        WHERE branch_id = $1 AND status = '1'
        ORDER BY monitor_id DESC
        ";

// $sql = "SELECT 
//     m.monitor_id,
//     m.branch_id,
//     m.group_id,
//     m.device_id,
//     m.type_id,
//     m.is_min,
//     m.min_value,
//     m.is_max,
//     m.max_value,
//     m.is_input_digi,
//     m.is_digital_zero,
//     m.is_line,
//     m.is_analog_min_work,
//     m.is_analog_max_work,
//     m.is_digital_work,
//     m.datax_id,
//     m.datax_value,
//     m.is_email,
//     m.is_sms,
//     m.createtime,
//     m.updatetime,
//     m.status,
//     m.sort,
//     m.is_condition,
//     m.config_value_1,
//     m.config_value_2,
//     m.config_value_3,
//     m.config_value_4,
//     m.input_line,
//     m.image_banner,
//     m.input_email,
//     m.monitor_name,
//     m.input_sms,
//     m.list_time_of_work,
//     g.group_id as g_id,
//     g.group_name as g_name,
//     d.device_id as d_id,
//     d.divice_name as d_name,
//     v.datax_id as v_id,
//     v.datax_name as v_name
// FROM page_data_manage_monitor AS m
// LEFT JOIN page_data_manage_group as g ON m.branch_id = g.branch_id
// LEFT JOIN page_data_manage_device as d ON m.branch_id = d.branch_id
// LEFT JOIN page_data_manage_datax as v ON m.branch_id = v.branch_id
// WHERE m.branch_id = $1   AND m.status = '1' 
// ORDER BY m.monitor_id DESC


// ";

$result = pg_query_params($db, $sql, [$branch_id]);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}


$data = [];

$data = [];

while ($row = pg_fetch_assoc($result)) {
    $row['list_time'] = [];   // 👈 เตรียม child
    $data[] = $row;
}

foreach ($data as $index => $monitor) {

    $sql_listTime = "SELECT * FROM page_data_manage_monitor_sub WHERE monitor_id = $1";

    $result_Time = pg_query_params($db, $sql_listTime, [$monitor['monitor_id']]);

    while ($row = pg_fetch_assoc($result_Time)) {
        $data[$index]['list_time'][] = $row;
    }
}


if (count($data) > 0) {
    echo json_encode(["status" => "success", "data" => array_values($data)], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
