<?php
header("Content-Type: application/json; charset=utf-8");
include_once("../includes/fn/pg_connect.php");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["Error" => "Method not allowed"], JSON_UNESCAPED_UNICODE);
    exit();
}

$type = $_GET['type'] ?? null;
$category = $_GET['category'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$lastDate = $_GET['last_date'] ?? null;

if (!$type || !$category || !$startDate || !$lastDate) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "missing required parameters"], JSON_UNESCAPED_UNICODE);
    exit();
}

$conn = pg_connect("$host $port $dbname $credentials");
if (!$conn) {
    http_response_code(501);
    echo json_encode(["status" => "error", "message" => "can not connect to database"], JSON_UNESCAPED_UNICODE);
    exit();
}
$params = [$startDate, $lastDate, $category];

if ($type === 'expense') {

    $sql = "
        SELECT e.*
        FROM expense e
        WHERE e.start_expense_date BETWEEN $1 AND $2
        AND (
            $3 = 'all'
            OR e.category_id IN (
                SELECT id FROM categories WHERE name = $3 AND type = 'expense'
            )
        )
    ";

} elseif ($type === 'income') {

    $sql = "
        SELECT i.*
        FROM income i
        WHERE i.start_income_date BETWEEN $1 AND $2
        AND (
            $3 = 'all'
            OR i.category_id IN (
                SELECT id FROM categories WHERE name = $3 AND type = 'income'
            )
        )
    ";

} else { // all

    $sql = "
        SELECT *
        FROM (
            SELECT e.*, 'expense' AS record_type
            FROM expense e
            WHERE e.start_expense_date BETWEEN $1 AND $2

            UNION ALL

            SELECT i.*, 'income' AS record_type
            FROM income i
            WHERE i.start_income_date BETWEEN $1 AND $2
        ) t
        WHERE (
            $3 = 'all'
            OR t.category_id IN (
                SELECT id FROM categories WHERE name = $3
            )
        )
    ";
}

$result = pg_query_params($conn, $sql, $params);


$data = pg_fetch_all($result);

if (count($data) > 0) {
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found this keyword \"" . $category . "\""]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($conn);
