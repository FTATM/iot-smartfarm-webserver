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
$branch_id = $_POST['bid'] ?? '';
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
if (empty($branch_id)) {
    echo json_encode(["status" => "error", "message" => "please input branch id"]);
    pg_close($db);
    exit;
}

// ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)

$sql = "SELECT 
    n.id,
    n.name,
    n.label,
    n.branch_id,
    n.child_of_table_id,
    n.is_deleted,
    d.id as d_id,
    d.name_table_id as d_name_table_id,
    d.start_day as d_start_day,
    d.end_day as d_end_day,
    d.value as d_value,
    d.row_parent_id as d_row_parent_id,
    d.second_label as d_second_label

FROM table_names AS n
LEFT JOIN table_datas as d ON n.id = d.name_table_id
WHERE n.branch_id = $1 AND is_deleted = 0
ORDER BY d.id ASC


";

$result = pg_query_params($db, $sql, [$branch_id]);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}
$data = [];

while ($row = pg_fetch_assoc($result)) {
    // $data[] = $row;

    $id = $row['id'];
    if (!isset($data[$id])) {
        $data[$id] = $row;
        $data[$id] += [
            'rows' => [[
                "d_id" => $row['d_id'],
                "d_name_table_id" => $row['d_name_table_id'],
                "d_start_day" => $row['d_start_day'],
                "d_end_day" => $row['d_end_day'],
                "d_value" => $row['d_value'],
                "d_row_parent_id" => $row['d_row_parent_id'],
                "d_second_label" => $row['d_second_label']
            ]]
        ];
    } else {
        array_push(
            $data[$id]['rows'],
            [
                "d_id" => $row['d_id'],
                "d_name_table_id" => $row['d_name_table_id'],
                "d_start_day" => $row['d_start_day'],
                "d_end_day" => $row['d_end_day'],
                "d_value" => $row['d_value'],
                "d_row_parent_id" => $row['d_row_parent_id'],
                "d_second_label" => $row['d_second_label']
            ]
        );
    }
}

if (count($data) > 0) {
    echo json_encode(["status" => "success", "data" => array_values($data)], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
