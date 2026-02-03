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
$data = $_POST['json'] ?? '';
$homebranch = $_POST['homebranch'] ?? '';
// $data = file_get_contents("php://input");
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
if (empty($data)) {
    echo json_encode(["status" => "error", "message" => "need parameter json"]);
    pg_close($db);
    exit;
}

$datadcode = json_decode($data);
$homedcode = json_decode($homebranch);

// ✅ ตรวจสอบว่ามี monitor_id หรือไม่
if (empty($datadcode->id)) {
    echo json_encode(["status" => "error", "message" => "missing id", "data" => $data]);
    pg_close($db);
    exit;
}

pg_query($db, "BEGIN");

try {

    // ✅ เตรียมคำสั่ง SQL UPDATE
    $sql = "UPDATE dashboard_main SET 
        label_text = $1,
        is_active = $2,
        type_values_id = $3,
        icon_id = $4,
        unitofvalue = $5
    WHERE id = $6
    ";
    $params = [
        $datadcode->label_text ?? '',
        $datadcode->is_active ?? '1',
        $datadcode->type_values_id ?? 1,
        $datadcode->icon_id ?? 1,
        $datadcode->unitofvalue ?? '',
        $datadcode->id
    ];

    // ✅ รันคำสั่ง SQL
    $result = pg_query_params($db, $sql, $params);

    $sql_hb = "UPDATE home_branch SET 
        value = $1
        WHERE branch_id = $2 AND home_row_id = $3
    ";

    $params_hb = [
        $homedcode->value ?? "",
        $homedcode->branch_id ?? 1,
        $homedcode->home_row_id ?? $datadcode->id
    ];

    $result = pg_query_params($db, $sql_hb, $params_hb);


    pg_query($db, "COMMIT");
    echo json_encode(["status" => "success", "message" => "update success"]);
} catch (Exception $e) {
    pg_query($db, "ROLLBACK");
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}


pg_close($db);
// ✅ ปิดการเชื่อมต่อ