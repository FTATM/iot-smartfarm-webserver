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

// ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)
$sql = "SELECT id,
        username,
        name,
        email,
        phone_number,
        address,
        id_card,
        url_logo,
        user_birthday,
        user_sex,
        role_id,
        createtime,
        updatetime,
        status,
        branch_id
        FROM user_account
        WHERE status = '1' ORDER BY id";

$result = pg_query($db, $sql);
// $result = pg_query_params($db, $sql,[$username]);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}

$data = [];

while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
    // $data[] = $row;
}

// ✅ ตรวจสอบผลลัพธ์
if (pg_num_rows($result) > 0) {
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "message" => "username or password wrong!"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
