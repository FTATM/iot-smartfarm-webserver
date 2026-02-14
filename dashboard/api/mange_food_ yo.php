<?php
header("Content-Type: application/json; charset=utf-8");
require __DIR__ . "/../config.php
";

// ✅ ใช้ตัวแปรเดียวกันกับที่เชื่อมต่อ
$conn = pg_connect("$host $port $dbname $credentials");
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}


if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["Error" => "Method not allowed"], JSON_UNESCAPED_UNICODE);
    exit();
}

// query หลัก
$result = pg_query($conn, "
    SELECT id, name
    FROM table_names
    WHERE id IN (16,17)
    ORDER BY id ASC
");

if (!$result) {
    http_response_code(500);
    echo json_encode(["Error" => pg_last_error($conn)], JSON_UNESCAPED_UNICODE);
    exit();
}

$data = [];

while ($row = pg_fetch_assoc($result)) {

    // query ลูก (แทน prepare/execute)
    $result2 = pg_query_params(
        $conn,
        "
        SELECT *
        FROM table_datas
        WHERE name_table_id = $1
        ORDER BY id ASC
        ",
        [$row['id']]
    );

    if (!$result2) {
        http_response_code(500);
        echo json_encode(["Error" => pg_last_error($conn)], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $value = pg_fetch_all($result2) ?: [];

    $data[] = [
        'id'    => (int)$row['id'],
        'name'  => $row['name'],
        'value' => $value
    ];
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
