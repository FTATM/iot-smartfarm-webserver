<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../includes/fn/pg_connect.php");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["Error" => "Method not allowed"], JSON_UNESCAPED_UNICODE);
    exit();
}

$bid = $_GET['bid'] ?? 1;

$conn = pg_connect("$host $port $dbname $credentials");
if (!$conn) {
    http_response_code(501);
    echo json_encode(["status" => "error", "message" => "can not connect to database"], JSON_UNESCAPED_UNICODE);
    exit();
}


$sql = "SELECT 
    SUM(CASE WHEN category = 'hardware' THEN amount ELSE 0 END) AS hardware,
    SUM(CASE WHEN category = 'infrastructure' THEN amount ELSE 0 END) AS infrastructure,
    SUM(CASE WHEN category = 'miscellaneous' THEN amount ELSE 0 END) AS miscellaneous
    FROM expense
    WHERE branch_id = $1";

$result = pg_query_params($conn, $sql, [$bid]);


$data = pg_fetch_all($result);

if (count($data) > 0) {
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found this keyword \"" . $market . "\""]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($conn);
