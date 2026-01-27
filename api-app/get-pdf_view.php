<?php
include_once("../includes/fn/pg_connect.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");



$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    http_response_code(500);
    exit;
}

$id  = $_GET['id'] ?? '';
$bid = $_GET['bid'] ?? '';

$sql = "SELECT name, path FROM tutorial_files WHERE id=$1 AND branch_id=$2 LIMIT 1";
$result = pg_query_params($db, $sql, [$id, $bid]);
$file = pg_fetch_assoc($result);

if (!$file) {
    http_response_code(404);
    exit;
}

pg_close($db);

// 📂 base path (Windows)
$basePath = 'D:/xampp/';
// $fullPath = realpath($basePath . str_replace('\\', '/', $file['path']));
$fullPath = $basePath . $file['path'];

// if (!$fullPath || !file_exists($fullPath)) {
//     http_response_code(404);
//     exit;
// }

// print($fullPath);

// // ✅ ส่ง PDF เท่านั้น
header('Content-Type: application/pdf');
// header('Content-Disposition: inline; filename="'.$file['name'].'.pdf"');
// header('Content-Length: ' . filesize($fullPath));
// header('Cache-Control: no-store');

readfile($fullPath);
// pg_close($db);
exit;
