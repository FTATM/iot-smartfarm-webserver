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

// รับค่า JSON
$json = $_POST['json'] ?? '';

if (empty($json)) {
    echo json_encode(["status" => "error", "message" => "need parameter json"]);
    pg_close($db);
    exit;
}

// decode JSON
$decode = json_decode($json);
if (!$decode) {
    echo json_encode(["status" => "error", "message" => "invalid json"]);
    pg_close($db);
    exit;
}

// เช็คค่าว่างสำคัญ เช่น username / password
if (empty($decode->username) || empty($decode->password)) {
    echo json_encode([
        "status" => "error",
        "message" => "missing username or password"
    ]);
    pg_close($db);
    exit;
}

$h_password = md5($decode->password);
$now = date('Y-m-d H:i:s');

// --------------------------------------------------------------------------------
// 🛑 เช็ค Username ซ้ำ
// --------------------------------------------------------------------------------
$sql_check = "SELECT id FROM user_account WHERE username = $1 LIMIT 1";
$result_check = pg_query_params($db, $sql_check, [$decode->username]);

if ($result_check && pg_num_rows($result_check) > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "username already exists"
    ]);
    pg_close($db);
    exit;
}

// --------------------------------------------------------------------------------
// ✅ เพิ่มข้อมูลผู้ใช้ใหม่
// --------------------------------------------------------------------------------
$sql_insert = "INSERT INTO user_account 
                ( name, username, email, password, createtime, updatetime ) 
               VALUES ( $1, $2, $3, $4, $5, $6 )";

$params_insert = [
    $decode->name ?? "",
    $decode->username,
    $decode->email ?? "",
    $h_password,
    $now,
    $now
];

$result_insert = pg_query_params($db, $sql_insert, $params_insert);

// --------------------------------------------------------------------------------
// ส่งผลลัพธ์กลับ
// --------------------------------------------------------------------------------
if ($result_insert) {
    echo json_encode([
        "status" => "success",
        "message" => "user registered successfully",
        "username" => $decode->username
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => pg_last_error($db)
    ]);
}

pg_close($db);
?>