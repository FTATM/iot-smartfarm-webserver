<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

// รับ JSON จาก Flutter / Postman
$raw = $_POST['json'] ?? file_get_contents("php://input");
if (empty($raw)) {
    echo json_encode(["status" => "error", "message" => "need parameter json"]);
    exit;
}

$data = json_decode($raw, true);
if (!is_array($data)) {
    echo json_encode(["status" => "error", "message" => "invalid json"]);
    exit;
}

// เริ่ม Transaction
pg_query($db, "BEGIN");

try {

    $id = $data['id'];

    // 1️⃣ ลบ child rows ก่อน
    $sql_child = "DELETE FROM table_datas WHERE name_table_id = $1";
    $res1 = pg_query_params($db, $sql_child, [$id]);

    if ($res1 === false) {
        throw new Exception("Delete child rows failed");
    }

    // 2️⃣ ลบ parent table
    $sql_parent = "DELETE FROM table_names WHERE id = $1";
    $res2 = pg_query_params($db, $sql_parent, [$id]);

    if ($res2 === false) {
        throw new Exception("Delete parent table failed");
    }

    pg_query($db, "COMMIT");

    echo json_encode([
        "status" => "success",
        "message" => "Column deleted"
    ]);

} catch (Exception $e) {

    pg_query($db, "ROLLBACK");

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
