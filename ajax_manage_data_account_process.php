<?php
 include_once("includes/fn/pg_connect.php");
 
 $db = pg_connect( "$host $port $dbname $credentials"  );

 if(!$db) {
     $msg = "Error : Unable to open database";
     $respose = array('ret'=>'100','msg'=>$msg);
     echo json_encode($respose);
     exit;
 }
    $data_group_manage_type = "";
    if(isset($_POST['data_group_manage_type']) && $_POST['data_group_manage_type'] !=""){
      $data_group_manage_type = $_POST['data_group_manage_type'];
    }else{
      $msg = "Param data_group_manage_type is NULL";
      $respose = array('ret'=>'102','msg'=>$msg);
      echo json_encode($respose);
      exit;
    }
    
    if($data_group_manage_type == '1'){
      $group_id = "";
      if(isset($_POST['group_id']) && $_POST['group_id'] !=""){
        $group_id = $_POST['group_id'];
      }else{
        $msg = "Param group_id is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $update_monitor = "DELETE FROM user_account
	                      WHERE id='{$group_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update user_account fail";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($data_group_manage_type == '2'){
      $group_id = "";
      if(isset($_POST['group_id']) && $_POST['group_id'] !=""){
        $group_id = $_POST['group_id'];
      }else{
        $msg = "Param group_id is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $isChecked = "";
      if(isset($_POST['isChecked']) && $_POST['isChecked'] !=""){
        $isChecked = $_POST['isChecked'];
      }else{
        $msg = "Param isChecked is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $update_monitor = "UPDATE user_account
                          SET status = '{$isChecked}',updatetime = now()
                          WHERE id='{$group_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update user_account fail ";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($data_group_manage_type == '3'){
      $branch_id = "";
      if(isset($_POST['branch_id']) && $_POST['branch_id'] !=""){
        $branch_id = $_POST['branch_id'];
      }else{
        $msg = "Param branch_id is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $user_id = "";
      if(isset($_POST['user_id']) && $_POST['user_id'] !=""){
        $user_id = $_POST['user_id'];
      }else{
        $msg = "Param user_id is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $update_monitor = "UPDATE user_account
                          SET branch_id = '{$branch_id}',updatetime = now()
                          WHERE id='{$user_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update user_account fail ";
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