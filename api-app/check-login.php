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
$username = $_POST['username'] ?? '';
$password = md5($_POST['password']) ?? '';
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "please input username and password"]);
    pg_close($db);
    exit;
}



// ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)
$sql = "SELECT u.id,
        u.username,
        u.password,
        u.name,
        u.email,
        u.phone_number,
        u.address,
        u.id_card,
        u.url_logo,
        u.user_birthday,
        u.user_sex,
        u.role_id,
        u.createtime,
        u.updatetime,
        u.status,
        u.branch_id,
        b.branch_id AS b_id,
        b.branch_name AS b_name,
        b.createtime AS b_createtime,
        b.updatetime AS b_updatetime,
        b.status AS b_status
        FROM user_account AS u
        LEFT JOIN branch_info AS b ON u.branch_id = b.branch_id
        WHERE u.username = $1 AND u.password = $2 AND u.status = '1'";
// $sql = "SELECT * FROM user_account WHERE username = $1";

$result = pg_query_params($db, $sql, [$username, $password]);
// $result = pg_query_params($db, $sql,[$username]);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}

// ✅ ตรวจสอบผลลัพธ์
if (pg_num_rows($result) > 0) {
    $user = pg_fetch_assoc($result);
    echo json_encode(["status" => "success", "user" => $user], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "message" => "username or password wrong!"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
