<?php
// Start session if it’s not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['username']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
  header("Location: login.php");
  exit();
}
 include_once("includes/fn/pg_connect.php");
 
 $db = pg_connect( "$host $port $dbname $credentials"  );

 if(!$db) {
     echo "Error : Unable to open database\n";
     exit;
 }

 $sql ="INSERT INTO page_data_manage_monitor(group_id, device_id, type_id,createtime,branch_id)
	VALUES ('1', '1', '1',now(),'".$_SESSION['branch_id']."');";

 $rs = execute($sql,$db);
 if($rs){
  header("Location: page1.php");
  exit;
 }else{
  echo "Create Box monitor fail!";
 }
  // echo "<pre>";
  //     print_r($rs);
?>