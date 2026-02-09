<?php
$conn = pg_connect("host=192.168.1.105 port=5432 dbname=postgres user=postgres password=postgres");

if (!$conn) {
    die("ERROR: " . pg_last_error());
}
echo "CONNECTED OK";
