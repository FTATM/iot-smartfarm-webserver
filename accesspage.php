<?php
session_start();
$result = 'dashboard_view_v3';
$branchid = $_SESSION['branch_id'];
if ($branchid == 2) {
  $result = 'dashboard/page/indoor-lettuce-dashboard';
}
elseif ($branchid == 3) {
  $result = 'dashboard/page/container-1-dashboard';
}

header("Location:/iotsf/" . $result . ".php");
die();
