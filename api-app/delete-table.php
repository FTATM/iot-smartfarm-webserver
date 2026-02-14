<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}
$raw = $_POST['json'] ?? file_get_contents("php://input");
if (empty($raw)) {
    echo json_encode(["status" => "error", "message" => "need parameter json"]);
    exit;
}

$data = json_decode($raw, true);

if (!isset($data['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing id'
    ]);
    exit;
}

$id = $data['id'];

pg_query($db, "BEGIN");

try {
    $sql_select_table_child = "SELECT id FROM table_names WHERE child_of_table_id = $1";
    $res_select = pg_query_params($db, $sql_select_table_child, [$id]);

    if (!$res_select) {
        throw new Exception('Select table child of parent failed');
    }

    while ($row = pg_fetch_assoc($res_select)) {

        $sql_delete_child = "DELETE FROM table_datas WHERE name_table_id = $1";
        $res_child_of_table_child = pg_query_params($db, $sql_delete_child, [$row['id']]);

        if (!$res_child_of_table_child) {
            throw new Exception('Delete child of table child failed');
        }

        // 2. ลบแม่ (table_names)
        $sql_delete_child_of_parent = "DELETE FROM table_names WHERE id = $1";
        $res_child_of_parent = pg_query_params($db, $sql_delete_child_of_parent, [$row['id']]);

        if (!$res_child_of_parent) {
            throw new Exception('Delete child of parent failed');
        }
    }


    // 1. ลบลูกก่อน (table_datas)
    $sql_delete_child = "DELETE FROM table_datas WHERE name_table_id = $1";
    $res_child = pg_query_params($db, $sql_delete_child, [$id]);

    if (!$res_child) {
        throw new Exception('Delete child failed');
    }

    // 2. ลบแม่ (table_names)
    $sql_delete_parent = "DELETE FROM table_names WHERE id = $1";
    $res_parent = pg_query_params($db, $sql_delete_parent, [$id]);

    if (!$res_parent) {
        throw new Exception('Delete parent failed');
    }

    pg_query($db, "COMMIT");

    echo json_encode([
        'status' => 'success',
        'message' => 'Deleted parent and children successfully'
    ]);
} catch (Exception $e) {

    pg_query($db, "ROLLBACK");

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
