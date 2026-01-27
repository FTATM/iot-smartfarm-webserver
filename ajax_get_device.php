<?php
include_once("includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");

$name = $_GET['name'] ?? null;

if (!$name) {
    echo json_encode(["error" => "Missing 'name' parameter."]);
    exit;
}

// ป้องกัน SQL Injection ด้วย pg_query_params
$sql = "SELECT * FROM public.volte_censor WHERE name = $1 ORDER BY id DESC LIMIT 1";
$result = pg_query_params($db, $sql, array($name));

if (!$result) {
    echo json_encode(["error" => "Query failed."]);
} else {
    $data = pg_fetch_all($result);
    echo json_encode($data);
}

pg_close($db);
