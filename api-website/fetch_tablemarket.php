<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../includes/fn/pg_connect.php");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["Error" => "Method not allowed"], JSON_UNESCAPED_UNICODE);
    exit();
}

$typeId = $_GET['typeId'] ?? 1;
$branchId = $_GET['bid'] ?? 0;

$conn = pg_connect("$host $port $dbname $credentials");
if (!$conn) {
    http_response_code(501);
    echo json_encode(["status" => "error", "message" => "can not connect to database"], JSON_UNESCAPED_UNICODE);
    exit();
}
$now = time();
$data = [];

$sql_1 = "SELECT * FROM table_names WHERE table_type_id = $1 AND branch_id = $2 LIMIT 1";
$result1 = pg_query_params($conn, $sql_1, [$typeId,$branchId]);
if (!$result1) {

    http_response_code(500);

    echo json_encode([
        "status" => "error",
        "message" => pg_last_error($conn)
    ]);

    pg_close($conn);
    exit();
}
$table = pg_fetch_assoc($result1);

if (!$table) {

    http_response_code(404);

    echo json_encode([
        "status" => "error",
        "message" => "No table found for this typeId"
    ], JSON_UNESCAPED_UNICODE);

    pg_close($conn);
    exit();
}

$sql_1_1 = "SELECT * FROM table_datas WHERE name_table_id = $1";
$result1_1 = pg_query_params($conn, $sql_1_1, [$table['id']]);

$data[$table['id']] = $table;
$data[$table['id']]['children'] = pg_fetch_all($result1_1);
// $data[$table['id']] = pg_fetch_all($result1_1);

$sql_2 = "SELECT * FROM table_names WHERE child_of_table_id = $1";
$result2 = pg_query_params($conn, $sql_2, [$table['id']]);


while ($row = pg_fetch_assoc($result2)) {
    $sql_3 = "SELECT * FROM table_datas WHERE name_table_id = $1 ORDER BY id ASC";
    $result3 = pg_query_params($conn, $sql_3, [$row['id']]);

    $data[$row['id']] = $row;
    $data[$row['id']]['children'] = pg_fetch_all($result3);
}


if (count($data) > 0) {
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(201);
    echo json_encode(["status" => "error", "data" => [], "message" => "No data found"]);
}


// ✅ ปิดการเชื่อมต่อ
pg_close($conn);
