<?php
session_start();
var_dump($_SESSION);
$branch_id = $_SESSION['branch_id'] ?? 0;

if ($branch_id == 0) {
    header("Location:/iotsf/login.php");
    exit;
}
if ($branch_id == 1) {
    header("Location:/iotsf/dashboard/page/container-1-dashboard.php");
} else if ($branch_id == 2) {
    header("Location:/iotsf/dashboard/page/indoor-greenhouse-dashboard.php");
}
exit;
