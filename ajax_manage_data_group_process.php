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
      $update_monitor = "DELETE FROM page_data_manage_group
	                      WHERE group_id='{$group_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update group fail";
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
      $update_monitor = "UPDATE page_data_manage_group
                          SET status = '{$isChecked}',update_time = now()
                          WHERE group_id='{$group_id}';";
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
      $vulue_map_volte_censer = "";
      if(isset($_POST['vulue_map_volte_censer']) && $_POST['vulue_map_volte_censer'] !=""){
        $vulue_map_volte_censer = $_POST['vulue_map_volte_censer'];
      }else{
        $msg = "Param vulue_map_volte_censer is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }

      $branch_id = "";
      if(isset($_POST['branch_id']) && $_POST['branch_id'] !=""){
        $branch_id = $_POST['branch_id'];
      }else{
        $msg = "Param branch_id is NULL";
        $respose = array('ret'=>'106','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }

      $select_chk_group = "SELECT group_name, value_map_volte_censor
	                        FROM page_data_manage_group WHERE group_name = '{$group_name}' AND value_map_volte_censor ='{$vulue_map_volte_censer}' AND branch_id = '{$branch_id}';";
      $rs_chk_group = select($select_chk_group,$db);
      if(count($rs_chk_group)>0){
        $msg = "Duplicate data group insert fail.";
        $respose = array('ret'=>'111','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $insert = "INSERT INTO page_data_manage_group(group_name, create_time, update_time, value_map_volte_censor,branch_id)
                    VALUES ('{$group_name}', Now(), Now(), '{$vulue_map_volte_censer}','{$branch_id}');";                            
      $rs_insert = execute($insert,$db);
      if(!$rs_insert){
        $msg = "Insert group fail ";
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
      $vulue_map_volte_censer = "";
      if(isset($_POST['vulue_map_volte_censer']) && $_POST['vulue_map_volte_censer'] !=""){
        $vulue_map_volte_censer = $_POST['vulue_map_volte_censer'];
      }else{
        $msg = "Param vulue_map_volte_censer is NULL";
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
      $branch_id = "";
      if(isset($_POST['branch_id']) && $_POST['branch_id'] !=""){
        $branch_id = $_POST['branch_id'];
      }else{
        $msg = "Param branch_id is NULL";
        $respose = array('ret'=>'106','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $select_chk_group = "SELECT group_name, value_map_volte_censor
	                        FROM page_data_manage_group WHERE group_name = '{$group_name}' 
                          AND value_map_volte_censor ='{$vulue_map_volte_censer}'
                          AND branch_id = '{$branch_id}';";
      $rs_chk_group = select($select_chk_group,$db);
      if(count($rs_chk_group)>0){
        $msg = "Duplicate data group insert fail.";
        $respose = array('ret'=>'111','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $update_monitor = "UPDATE page_data_manage_group
                          SET group_name = '{$group_name}',value_map_volte_censor='{$vulue_map_volte_censer}',update_time = now()
                          WHERE group_id='{$group_id}';";
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