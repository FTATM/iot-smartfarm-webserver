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
      $sql_check_user_active = "select * from user_account WHERE branch_id ='{$group_id}' AND status ='1';";
      $rs_check_user_active = select($sql_check_user_active,$db);
      if(count($rs_check_user_active)>0){
        $msg = "Cannot delete this 'branch' because Some user already active!";
        $respose = array('ret'=>'109','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $sql_check_user_active = "select * from dashboard_item WHERE branch_id ='{$group_id}' AND status ='1';";
      $rs_check_user_active = select($sql_check_user_active,$db);
      if(count($rs_check_user_active)>0){
        $msg = "Cannot delete this 'branch' because Some 'Dashboard' already active!";
        $respose = array('ret'=>'108','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $sql_check_user_active = "select * from page_data_manage_monitor WHERE branch_id ='{$group_id}' AND status ='1';";
      $rs_check_user_active = select($sql_check_user_active,$db);
      if(count($rs_check_user_active)>0){
        $msg = "Cannot delete this 'branch' because Some 'Monitor' already active!";
        $respose = array('ret'=>'108','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $update_monitor = "DELETE FROM branch_info
	                      WHERE branch_id='{$group_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Delete branch fail";
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
      $update_monitor = "UPDATE branch_info
                          SET status = '{$isChecked}',updatetime = now()
                          WHERE branch_id='{$group_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update group fail ";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($data_group_manage_type == '3'){
      $group_name = "";
      if(isset($_POST['group_name']) && $_POST['group_name'] !=""){
        $group_name = $_POST['group_name'];
      }else{
        $msg = "Param group_name is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      
      $select_chk_group = "SELECT branch_name
	                        FROM branch_info WHERE branch_name = '{$group_name}';";
      $rs_chk_group = select($select_chk_group,$db);
      if(count($rs_chk_group)>0){
        $msg = "Duplicate data branch_name insert fail.";
        $respose = array('ret'=>'111','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $insert = "INSERT INTO branch_info(branch_name, createtime, updatetime)
                    VALUES ('{$group_name}', Now(), Now());";                            
      $rs_insert = execute($insert,$db);
      if(!$rs_insert){
        $msg = "Insert device_name fail ";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($data_group_manage_type == '4'){
      $group_name = "";
      if(isset($_POST['group_name']) && $_POST['group_name'] !=""){
        $group_name = $_POST['group_name'];
      }else{
        $msg = "Param group_name is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $group_id = "";
      if(isset($_POST['group_id']) && $_POST['group_id'] !=""){
        $group_id = $_POST['group_id'];
      }else{
        $msg = "Param group_id is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      
      $select_chk_group = "SELECT branch_name
	                        FROM branch_info WHERE branch_name = '{$group_name}';";
      $rs_chk_group = select($select_chk_group,$db);
      if(count($rs_chk_group)>0){
        $msg = "Duplicate data branch_name insert fail.";
        $respose = array('ret'=>'111','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $update_monitor = "UPDATE branch_info
                          SET branch_name = '{$group_name}',updatetime = now()
                          WHERE branch_id='{$group_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update group fail ";
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