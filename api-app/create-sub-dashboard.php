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
$monitors = $decode->data;
$sql = "INSERT INTO dashboard_item_sub_data 
                ( label_name, dashboard_item_id, monitor_id, status, label_color_code, createtime, updatetime ) 
                VALUES ( $1,$2,$3,$4,$5,$6,$7 )";

$now = date('Y-m-d H:i:s');


$params = [
    $decode->name ?? "No Name Monitor",
    $decode->dashboard_id ?? 1,
    $monitors[0],
    $decode->status ?? 1,
    $decode->label_color_code ?? '#24d3eb',
    $now,
    $now
];
$result = pg_query_params($db, $sql, $params);

if (!empty($decode->type_dashboard_id) && in_array($decode->type_dashboard_id, ['2', '5', '6', '12'])) {

    $params = [
        $decode->name ?? "No Name Monitor",
        $decode->dashboard_id ?? 1,
        $monitors[1],
        $decode->status ?? 1,
        $decode->label_color_code ?? '#24d3eb',
        $now,
        $now
    ];

    $result = pg_query_params($db, $sql, $params);
}


if ($result) {
    echo json_encode(["status" => "success", "message" => "Create Success"]);
} else {
    echo json_encode(["status" => "error", "message" => pg_last_error($db)]);
}

pg_close($db);
// ✅ ปิดการเชื่อมต่อ
