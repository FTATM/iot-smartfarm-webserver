<?php
header("Access-Control-Allow-Origin: *"); // อนุญาตทุก origin
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// ตรวจสอบ method
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];

    if ($fileError === 0) {
        $uploadDir = __DIR__ . '/../img/logos/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $destination = $uploadDir . basename($fileName);
        if (move_uploaded_file($fileTmpName, $destination)) {
            $result = uploadimg($fileName);
            echo json_encode(['status' => 'success', 'file' => $fileName,'upload' => 'success' , 'message' => $result]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to move file']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Upload error code: ' . $fileError]);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
}

function uploadimg($name)
{
    include_once("../includes/fn/pg_connect.php");

    // แยกชื่อไฟล์กับนามสกุล
    $fileInfo = pathinfo($name);
    $fileBaseName = $fileInfo['filename']; // ชื่อไฟล์ ไม่เอานามสกุล

    $db = pg_connect("$host $port $dbname $credentials");
    if (!$db) {
        return "Cannot connect to database";
    }

    $sql = "INSERT INTO logos (name, path) VALUES ($1,$2)";
    $params = [$fileBaseName ?? 'unknown', 'img/logos/' . $name];

    $result = pg_query_params($db, $sql, $params);

    pg_close($db);

    return $result ? "Insert success" : pg_last_error($db);
}

