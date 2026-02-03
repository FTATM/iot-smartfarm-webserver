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
      $update_monitor = "DELETE FROM dashboard_item
	                      WHERE id='{$group_id}';";
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
      $update_monitor = "UPDATE dashboard_item
                          SET status = '{$isChecked}',updatetime = now()
                          WHERE id='{$group_id}';";
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
      $sel_type_id = "";
      if(isset($_POST['sel_type_id']) && $_POST['sel_type_id'] !=""){
        $sel_type_id = $_POST['sel_type_id'];
      }else{
        $msg = "Param sel_type_id is NULL";
        $respose = array('ret'=>'106','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $branch_id = "";
      if(isset($_POST['branch_id']) && $_POST['branch_id'] !=""){
        $branch_id = $_POST['branch_id'];
      }else{
        $msg = "Param branch_id is NULL";
        $respose = array('ret'=>'107','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      
      // $insert = "INSERT INTO page_data_manage_group(group_name, create_time, update_time, value_map_volte_censor)
      //               VALUES ('{$group_name}', Now(), Now(), '{$vulue_map_volte_censer}');";     
      $insert = "INSERT INTO dashboard_item( item_type_id, item_name, createtime, updatetime,status,branch_id)
                                            VALUES ( '{$sel_type_id}', '{$group_name}', Now(), Now(),'0','{$branch_id}');";

      $rs_insert = execute($insert,$db);
      if(!$rs_insert){
        $msg = "Insert group fail ";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($data_group_manage_type == '4'){
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
      $update_monitor = "UPDATE dashboard_item
                          SET sort = '{$isChecked}',updatetime = now()
                          WHERE id='{$group_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update group fail ";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($data_group_manage_type == '5'){
      $dashboard_id = "";
      if(isset($_POST['dashboard_id']) && $_POST['dashboard_id'] !=""){
        $dashboard_id = $_POST['dashboard_id'];
      }else{
        $msg = "Param dashboard_id is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $group_name = "";
      if(isset($_POST['group_name']) && $_POST['group_name'] !=""){
        $group_name = $_POST['group_name'];
      }else{
        $msg = "Param dash_name is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $update_monitor = "UPDATE dashboard_item
                          SET item_name = '{$group_name}',updatetime = now()
                          WHERE id='{$dashboard_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update dash name fail ";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($data_group_manage_type == '6'){
      $group_id = "";
      if(isset($_POST['group_id']) && $_POST['group_id'] !=""){
        $group_id = $_POST['group_id'];
      }else{
        $msg = "Param group_id is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $update_monitor = "DELETE FROM dashboard_item_sub_data
	                      WHERE id='{$group_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update group fail";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($data_group_manage_type == '7'){
      $label_name = "";
      if(isset($_POST['label_name']) && $_POST['label_name'] !=""){
        $label_name = $_POST['label_name'];
      }else{
        $msg = "Param label_name is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $dashboard_item_id = "";
      if(isset($_POST['dashboard_item_id']) && $_POST['dashboard_item_id'] !=""){
        $dashboard_item_id = $_POST['dashboard_item_id'];
      }else{
        $msg = "Param dashboard_item_id is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
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
      $label_color_code = "";
      if(isset($_POST['label_color_code']) && $_POST['label_color_code'] !=""){
        $label_color_code = $_POST['label_color_code'];
      }else{
        $msg = "Param label_color_code is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
         
      // $insert = "INSERT INTO dashboard_item( item_type_id, item_name, createtime, updatetime,status)
      //                                       VALUES ( '{$sel_type_id}', '{$group_name}', Now(), Now(),'0');";
      $insert = "INSERT INTO public.dashboard_item_sub_data(label_name, dashboard_item_id, monitor_id, createtime, updatetime, label_color_code)
                              VALUES ('{$label_name}', '{$dashboard_item_id}', '{$monitor_id}', Now(), Now(), '{$label_color_code}');";

      $rs_insert = execute($insert,$db);
      if(!$rs_insert){
        $msg = "Insert group fail ";
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