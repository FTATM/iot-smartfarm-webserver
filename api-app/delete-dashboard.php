<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

$json = $_POST['json'] ?? '';
if (empty($json)) {
    echo json_encode(["status" => "error", "message" => "need parameter json"]);
    pg_close($db);
    exit;
}

$decode = json_decode($json);
if (empty($decode->id)) {
    echo json_encode(["status" => "error", "message" => "missing id", "data" => $json]);
    pg_close($db);
    exit;
}

$id = $decode->id;

// 🟦 เริ่ม Transaction
pg_query($db, "BEGIN");

// ลบข้อมูลลูก
$sql1 = "DELETE FROM dashboard_item_sub_data WHERE dashboard_item_id = $1";
$result1 = pg_query_params($db, $sql1, [$id]);

// ลบข้อมูล parent
$sql2 = "DELETE FROM dashboard_item WHERE id = $1";
$result2 = pg_query_params($db, $sql2, [$id]);

if ($result1 && $result2) {
    pg_query($db, "COMMIT");
    echo json_encode(["status" => "success", "message" => "delete success"]);
} else {
    pg_query($db, "ROLLBACK");
    echo json_encode(["status" => "error", "message" => pg_last_error($db)]);
}

pg_close($db);
