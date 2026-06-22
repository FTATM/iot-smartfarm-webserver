<?php
session_start();
var_dump($_SESSION);
$branch_id = $_SESSION['branch_id'] ?? 0;

if ($branch_id == 1) {
    header("Location:/iotsf/dashboard/page/container-1-dashboard.php");
} else if ($branch_id == 2) {
    header("Location:/iotsf/dashboard/page/container-2-dashboard.php");
} else if ($branch_id == 3) {
    header("Location:/iotsf/dashboard/page/indoor-greenhouse-dashboard.php");
} else if ($branch_id == 4) {
    header("Location:/iotsf/dashboard/page/weather-dashboard.php");
} else if ($branch_id == 5) {
    header("Location:/iotsf/dashboard/page/solar-system-dashboard.php");
} else {
    header("Location:/iotsf/login.php");
}
// exit;
?>
<p>Sorry something went wrong. please contect administrators.</p>