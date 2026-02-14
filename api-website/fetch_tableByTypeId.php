<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../includes/fn/pg_connect.php");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["Error" => "Method not allowed"], JSON_UNESCAPED_UNICODE);
    exit();
}

$typeId = $_GET['typeId'] ?? 1;
$amountDay = $_GET['aDay'] ?? 0;

$conn = pg_connect("$host $port $dbname $credentials");
if (!$conn) {
    http_response_code(501);
    echo json_encode(["status" => "error", "message" => "can not connect to database"], JSON_UNESCAPED_UNICODE);
    exit();
}
$now = time();

$sql = "SELECT 
            p.id   AS parent_id,
            p.name AS parent_name,
            p.label AS parent_label,
            c.id   AS child_id,
            c.label AS child_label,
            s.start_day AS parent_start_day,
            s.end_day AS parent_end_day,
            s.value AS parent_value,
            d.start_day AS start_day,
            d.end_day AS end_day,
            d.value AS value
        FROM table_names p
        LEFT JOIN table_names c ON c.child_of_table_id = p.id
        LEFT JOIN table_datas d ON d.name_table_id = c.id AND d.start_day <= $2 AND d.end_day >= $2
        LEFT JOIN table_datas s ON s.name_table_id = p.id AND s.start_day <= $2 AND s.end_day >= $2
        WHERE p.table_type_id = $1
        ORDER BY p.id ,c.id;
";

$result = pg_query_params($conn, $sql, [$typeId, $amountDay]);

$data = [];
while ($row = pg_fetch_assoc($result)) {
    $pid = $row['parent_id'];

    if (!isset($data[$pid])) {
        $data[$pid] = [
            'id' => $pid,
            'name' => $row['parent_name'],
            'label' => $row['parent_label'],
            'start' => $row['parent_start_day'],
            'end' => $row['parent_end_day'],
            'value' => $row['parent_value'],
            'children' => []
        ];
    }

    if ($row['child_id']) {
        $data[$pid]['children'][] = [
            'id' => $row['child_id'],
            'label' => $row['child_label'],
            'start' => $row['start_day'],
            'end' => $row['end_day'],
            'value' => $row['value']
        ];
    }
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
