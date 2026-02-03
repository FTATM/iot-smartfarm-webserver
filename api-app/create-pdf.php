<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (!isset($_POST['bid']) || empty($_POST['bid'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing bid']);
    exit;
}

if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
    exit;
}

$bid = preg_replace('/[^0-9]/', '', $_POST['bid']); // กัน path traversal
$file = $_FILES['file'];

if ($file['error'] !== 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Upload error']);
    exit;
}

// ✅ ตรวจ MIME
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $file['tmp_name']);

if ($mime !== 'application/pdf') {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Only PDF allowed']);
    exit;
}

$uploadDir = "D:/xampp/pdf/$bid/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileName = basename($file['name']);
$destination = $uploadDir . $fileName;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to move file']);
    exit;
}

// 🔹 บันทึก DB
$result = uploadimg($fileName, $bid);

echo json_encode([
    'status' => 'success',
    'file' => $fileName,
    'message' => $result,
]);
exit;



function uploadimg($name, $bid)
{
    include_once("../includes/fn/pg_connect.php");

    $fileInfo = pathinfo($name);
    $fileBaseName = $fileInfo['filename'];

    $db = pg_connect("$host $port $dbname $credentials");
    if (!$db) {
        return "DB connection failed";
    }

    $sql = "INSERT INTO tutorial_files (name, path, branch_id) VALUES ($1, $2, $3)";
    $params = [$fileBaseName, "pdf/$bid/$name", $bid];

    $result = pg_query_params($db, $sql, $params);
    pg_close($db);

    return $result ? "Insert success" : "Insert failed";
}
