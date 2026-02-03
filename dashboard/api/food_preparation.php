<?php 
header("Content-Type: application/json; charset=utf-8");
require __DIR__ . "/../../includes/fn/pg_connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["Error" => "Method not allowed"], JSON_UNESCAPED_UNICODE);
    exit();
}

// ✅ ถ้า pg_connect.php มี $conn อยู่แล้ว ใช้ได้เลย
if (!isset($conn) || !$conn) {
    $conn = pg_connect("$host $port $dbname $credentials");
    if (!$conn) {
        echo json_encode(["status" => "error", "message" => "can not connect to database"], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

$sql = "
    SELECT 
    n.id,
    n.name,
    n.label,
    d.value
FROM names_table n
LEFT JOIN datas_table d 
    ON d.name_table_id = n.id
WHERE n.id IN (10,14,16,15)
  AND d.name_table_id IN (10,14,15,16)
  ORDER BY id ASC ;

";

$result = pg_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["Error" => pg_last_error($conn)], JSON_UNESCAPED_UNICODE);
    exit();
}

$data = pg_fetch_all($result) ?: [];
echo json_encode($data, JSON_UNESCAPED_UNICODE);
