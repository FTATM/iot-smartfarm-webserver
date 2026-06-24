<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");

if (!$db) {
    echo json_encode([
        "status" => "error",
        "message" => "can not connect to database"
    ]);
    exit;
}

// รับ JSON
$raw = $_POST['json'] ?? file_get_contents("php://input");

if (empty($raw)) {
    echo json_encode([
        "status" => "error",
        "message" => "need parameter json"
    ]);
    exit;
}

$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode([
        "status" => "error",
        "message" => "invalid json"
    ]);
    exit;
}

pg_query($db, "BEGIN");

try {

    $values = [];

    foreach ($data as $row) {

        if (!isset($row['id'])) {
            continue;
        }

        if (!array_key_exists('ai_allow', $row)) {
            continue;
        }

        $id = (int)$row['id'];

        // บังคับให้เป็น 0 หรือ 1
        $aiAllow = (int)$row['ai_allow'];
        $aiAllow = $aiAllow ? 1 : 0;

        $values[] = "($id,$aiAllow)";
    }

    if (empty($values)) {
        throw new Exception("No valid data");
    }

    $sql = "
        UPDATE page_data_manage_monitor AS p
        SET ai_allow = v.ai_allow
        FROM (
            VALUES
            " . implode(",\n", $values) . "
        ) AS v(id, ai_allow)
        WHERE p.id = v.id
    ";

    $result = pg_query($db, $sql);

    if (!$result) {
        throw new Exception(pg_last_error($db));
    }

    $affectedRows = pg_affected_rows($result);

    pg_query($db, "COMMIT");

    echo json_encode([
        "status" => "success",
        "message" => "patch completed",
        "updated_rows" => $affectedRows
    ]);

} catch (Exception $e) {

    pg_query($db, "ROLLBACK");

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

pg_close($db);