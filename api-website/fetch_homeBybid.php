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
$now = time();

$sql = "SELECT value, home_row_id
        FROM home_branch
        WHERE branch_id = $1 ORDER BY home_row_id ASC";

$result = pg_query_params($conn, $sql, [$bid]);
$data = [];

while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
}

if ($data) {

    $db_time = strtotime($data['3']['value']);  // แปลงวันที่เป็น timestamp
    $diff = $now - $db_time;              // ต่างกันกี่วินาที

    // คำนวณวัน ชั่วโมง นาที
    $days = floor($diff / 86400);
    $hours = floor(($diff % 86400) / 3600);
    $minutes = floor(($diff % 3600) / 60);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => [
            "day" => $days,
            "hour" => $hours,
            "min" => $minutes,
            'value' => $data
        ]
    ], JSON_UNESCAPED_UNICODE);
} else {

    echo json_encode([
        "status" => "error",
        "data" => [],
        "message" => "No data found"
    ]);
}


// ✅ ปิดการเชื่อมต่อ
pg_close($conn);
