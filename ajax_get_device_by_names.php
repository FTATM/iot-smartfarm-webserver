<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
include_once("includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");

$names = $_GET['names'] ?? null;

if (!$names) {
    echo json_encode(["success" => false, "error" => "Missing 'names' parameter."]);
    exit;
}

$namesArray = array_map('trim', explode(',', $names));

$data = []; // ✅ ประกาศครั้งเดียว

foreach ($namesArray as $name) {

    $sql = "SELECT * 
            FROM public.volte_censor 
            WHERE name = $1
            ORDER BY id DESC 
            LIMIT 1";

    $result = pg_query_params($db, $sql, [$name]);

    if (!$result) {
        echo json_encode([
            "status" => "error",
            "error" => pg_last_error($db)
        ]);
        pg_close($db);
        exit();
    }

    $row = pg_fetch_assoc($result);

    if ($row) {
        $data[$row['name']] = $row;
    }
}

if (count($data) > 0) {
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
