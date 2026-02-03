<?php
 include_once("includes/fn/pg_connect.php");
 
 $db = pg_connect( "$host $port $dbname $credentials"  );

 if(!$db) {
     $msg = "Error : Unable to open database";
     $respose = array('ret'=>'100','msg'=>$msg);
     echo json_encode($respose);
     exit;
 }
    $page2_sql_type = "";
    if(isset($_POST['page2_sql_type']) && $_POST['page2_sql_type'] !=""){
      $page2_sql_type = $_POST['page2_sql_type'];
    }else{
      $msg = "Param page2_sql_type is NULL";
      $respose = array('ret'=>'102','msg'=>$msg);
      echo json_encode($respose);
      exit;
    }
    $monitor_id = "";
    if(isset($_POST['monitor_id']) && $_POST['monitor_id'] !=""){
      $monitor_id = $_POST['monitor_id'];
    }else{
      $msg = "Param monitor_id is NULL";
      $respose = array('ret'=>'105','msg'=>$msg);
      echo json_encode($respose);
      exit;
    }
    
    if($page2_sql_type == '1'){
      $monitor_sub_id = "";
      if(isset($_POST['monitor_sub_id']) && $_POST['monitor_sub_id'] !=""){
        $monitor_sub_id = $_POST['monitor_sub_id'];
      }else{
        $msg = "Param monitor_sub_id is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $update_monitor = "DELETE FROM page_data_manage_monitor_sub
	                      WHERE monitor_sub_id='{$monitor_sub_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }

    $msg = "success";
    $respose = array('ret'=>'101','msg' => $msg);
    echo json_encode($respose);

pg_close($db);
?>  