<?php
$conn = pg_connect("host=localhost port=5432 dbname=smartfarm-test user=postgres password=postgres");

if (!$conn) {
    echo "เชื่อมต่อไม่ได้: " . pg_last_error();
} else {
    echo "เชื่อมต่อสำเร็จ!";
    pg_close($conn);
}