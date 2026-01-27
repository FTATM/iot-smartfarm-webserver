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
$id = $_POST['id'] ?? '';
$username = $_POST['username'] ?? '';
$password = md5($_POST['password']) ?? '';
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "please input username and password"]);
    pg_close($db);
    exit;
}



// ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)
$sql = "SELECT id FROM user_account WHERE id = $1 AND username = $2 AND password = $3 AND  status = '1'";

$result = pg_query_params($db, $sql, [$id, $username, $password]);
// $result = pg_query_params($db, $sql,[$username]);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}

// ✅ ตรวจสอบผลลัพธ์
if (pg_num_rows($result) > 0) {
    echo json_encode(["status" => "success", "found" => true], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "found" => false, "message" => "username or password wrong!"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
