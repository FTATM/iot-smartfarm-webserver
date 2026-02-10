<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../includes/fn/pg_connect.php");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["Error" => "Method not allowed"], JSON_UNESCAPED_UNICODE);
    exit();
}

$conn = pg_connect("$host $port $dbname $credentials");
if (!$conn) {
    http_response_code(501);
    echo json_encode(["status" => "error", "message" => "can not connect to database"], JSON_UNESCAPED_UNICODE);
    exit();
}

$market = $_GET['market'] ?? '';

// $conn = pg_connect(...);  // สมมติว่าคุณต่อไว้แล้ว

$sql = "SELECT trend_date, trend_value
        FROM market_trends
        WHERE market_name = $1
        AND trend_type = 'demand'
        ORDER BY trend_date;
        ";

$result = pg_query_params($conn, $sql, [$market]);

if (!$result) {
    http_response_code(500);
    echo json_encode(["Error" => pg_last_error($conn)], JSON_UNESCAPED_UNICODE);
    exit();
}

$data = pg_fetch_all($result);

if (count($data) > 0) {
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($conn);
