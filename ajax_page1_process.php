<?php
 include_once("includes/fn/pg_connect.php");
 
 $db = pg_connect( "$host $port $dbname $credentials"  );

 if(!$db) {
     $msg = "Error : Unable to open database";
     $respose = array('ret'=>'100','msg'=>$msg);
     echo json_encode($respose);
     exit;
 }
    $page1_sql_type = "";
    if(isset($_POST['page1_sql_type']) && $_POST['page1_sql_type'] !=""){
      $page1_sql_type = $_POST['page1_sql_type'];
    }else{
      $msg = "Param page1_sql_type is NULL";
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

    if($page1_sql_type == '1'){
      $config_value_1 = "";
      if(isset($_POST['config_value_1']) && $_POST['config_value_1'] != ""){
        $config_value_1 = $_POST['config_value_1'];
      }else{
        $msg = "Param config_value_1 is NULL";
        $respose = array('ret'=>'104','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $data_location = "";
      if(isset($_POST['data_location']) && $_POST['data_location'] != ""){
        $data_location = $_POST['data_location'];
      }else{
        $msg = "Param data_location is NULL";
        $respose = array('ret'=>'106','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $data_name = "";
      if(isset($_POST['data_name']) && $_POST['data_name'] != ""){
        $data_name = $_POST['data_name'];
      }else{
        $msg = "Param data_name is NULL";
        $respose = array('ret'=>'107','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $datax_id = "";
      if(isset($_POST['datax_id']) && $_POST['datax_id'] != ""){
        $datax_id = $_POST['datax_id'];
      }else{
        $msg = "Param datax_id is NULL";
        $respose = array('ret'=>'1077','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      //page1_sql_type = 1
      //Update "monitor" id = elm_index,config_value_1 = slide_val, 
      //|insert "volte_censor" location = group, name = device, DATA01
      $update_monitor = "UPDATE page_data_manage_monitor
                          SET datax_value = '{$config_value_1}',updatetime = now()
                          WHERE monitor_id='{$monitor_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $sql_device = "SELECT device_id, divice_name FROM page_data_manage_device where device_id='{$data_name}';";
      $rs_device   = select($sql_device,$db);
      if(count($rs_device)!='1'){
        $msg = "device_id is wrong";
        $respose = array('ret'=>'109','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $divice_name = $rs_device[0]->divice_name;

      $sql_datax = "SELECT datax_id, datax_name FROM page_data_manage_datax where datax_id='{$datax_id}';";
      $rs_datax   = select($sql_datax,$db);
      if(count($rs_datax)!='1'){
        $msg = "datax_id is wrong";
        $respose = array('ret'=>'1099','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $datax_name = $rs_datax[0]->datax_name;

      $sql_group = "SELECT group_id, group_name, value_map_volte_censor FROM page_data_manage_group WHERE group_id='{$data_location}';";
      $rs_group   = select($sql_group,$db);
      if(count($rs_group)!='1'){
        $msg = "group_id is wrong";
        $respose = array('ret'=>'110','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $value_map_volte_censor = $rs_group[0]->value_map_volte_censor;
      //'page1_sql_type=1'+'&monitor_id='+elm_index+'&config_value_1='+ slide_val+'&data_location='+data_location+'&data_name='+data_name
      $insert_volte = "INSERT INTO volte_censor
                      (create_uid, write_uid, name, location, date_mornitor, create_date, write_date, volte, sensor, unit, value, voltemax, \"".strtoupper($datax_name)."\")
                      VALUES 
                      ('99', '99', '{$divice_name}', '{$value_map_volte_censor}', '".date('Y-m-d')."', now(), now(), '0', '1', '0', '0', '0', '{$config_value_1}');";
      $rs_insert_volte = execute($insert_volte,$db);
      if(!$rs_insert_volte){
        $msg = "insert volte fail";
        $respose = array('ret'=>'108','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($page1_sql_type == '2'){
      $config_value_2 = "";
      if(isset($_POST['config_value_2']) && $_POST['config_value_2'] != ""){
        $config_value_2 = $_POST['config_value_2'];
      }else{
        $msg = "Param config_value_2 is NULL";
        $respose = array('ret'=>'104','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $data_location = "";
      if(isset($_POST['data_location']) && $_POST['data_location'] != ""){
        $data_location = $_POST['data_location'];
      }else{
        $msg = "Param data_location is NULL";
        $respose = array('ret'=>'112','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $data_name = "";
      if(isset($_POST['data_name']) && $_POST['data_name'] != ""){
        $data_name = $_POST['data_name'];
      }else{
        $msg = "Param data_name is NULL";
        $respose = array('ret'=>'113','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      //page1_sql_type = 1
      //Update "monitor" id = elm_index,config_value_1 = slide_val, 
      //|insert "volte_censor" location = group, name = device, DATA01
      $update_monitor = "UPDATE page_data_manage_monitor
                          SET datax_value = '{$config_value_2}',updatetime = now()
                          WHERE monitor_id='{$monitor_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'114','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $sql_device = "SELECT device_id, divice_name FROM page_data_manage_device where device_id='{$data_name}';";
      $rs_device   = select($sql_device,$db);
      if(count($rs_device)!='1'){
        $msg = "device_id is wrong";
        $respose = array('ret'=>'115','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $divice_name = $rs_device[0]->divice_name;

      $datax_id = "";
      if(isset($_POST['datax_id']) && $_POST['datax_id'] != ""){
        $datax_id = $_POST['datax_id'];
      }else{
        $msg = "Param datax_id is NULL";
        $respose = array('ret'=>'1077','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $sql_datax = "SELECT datax_id, datax_name FROM page_data_manage_datax where datax_id='{$datax_id}';";
      $rs_datax   = select($sql_datax,$db);
      if(count($rs_datax)!='1'){
        $msg = "datax_id is wrong";
        $respose = array('ret'=>'1099','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $datax_name = $rs_datax[0]->datax_name;

      $sql_group = "SELECT group_id, group_name, value_map_volte_censor FROM page_data_manage_group WHERE group_id='{$data_location}';";
      $rs_group   = select($sql_group,$db);
      if(count($rs_group)!='1'){
        $msg = "group_id is wrong";
        $respose = array('ret'=>'116','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $value_map_volte_censor = $rs_group[0]->value_map_volte_censor;
      //'page1_sql_type=1'+'&monitor_id='+elm_index+'&config_value_1='+ slide_val+'&data_location='+data_location+'&data_name='+data_name
      $insert_volte = "INSERT INTO public.volte_censor
                      (create_uid, write_uid, name, location, date_mornitor, create_date, write_date, volte, sensor, unit, value, voltemax, \"".strtoupper($datax_name)."\")
                      VALUES 
                      ('99', '99', '{$divice_name}', '{$value_map_volte_censor}', '".date('Y-m-d')."', now(), now(), '0', '1', '0', '0', '0', '{$config_value_2}');";
      $rs_insert_volte = execute($insert_volte,$db);
      if(!$rs_insert_volte){
        $msg = "insert volte fail";
        $respose = array('ret'=>'108','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($page1_sql_type == '3'){
      $is_condition = "";
      if(isset($_POST['is_condition']) && $_POST['is_condition'] != ""){
        $is_condition = $_POST['is_condition'];
      }else{
        $msg = "Param is_condition is NULL";
        $respose = array('ret'=>'117','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      
      $update_monitor = "UPDATE page_data_manage_monitor
                          SET is_condition = '{$is_condition}',updatetime = now()
                          WHERE monitor_id='{$monitor_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'118','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($page1_sql_type == '4'){
      $group_id = "";
      if(isset($_POST['group_id']) && $_POST['group_id'] != ""){
        $group_id = $_POST['group_id'];
      }else{
        $msg = "Param group_id is NULL";
        $respose = array('ret'=>'119','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      
      $update_monitor = "UPDATE page_data_manage_monitor
                          SET group_id = '{$group_id}',updatetime = now()
                          WHERE monitor_id='{$monitor_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'120','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($page1_sql_type == '5'){
      $device_id = "";
      if(isset($_POST['device_id']) && $_POST['device_id'] != ""){
        $device_id = $_POST['device_id'];
      }else{
        $msg = "Param device_id is NULL";
        $respose = array('ret'=>'121','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      
      $update_monitor = "UPDATE page_data_manage_monitor
                          SET device_id = '{$device_id}',updatetime = now()
                          WHERE monitor_id='{$monitor_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'122','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($page1_sql_type == '6'){
      $type_id = "";
      if(isset($_POST['type_id']) && $_POST['type_id'] != ""){
        $type_id = $_POST['type_id'];
      }else{
        $msg = "Param type_id is NULL";
        $respose = array('ret'=>'123','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      
      $update_monitor = "UPDATE page_data_manage_monitor
                          SET type_id = '{$type_id}',updatetime = now()
                          WHERE monitor_id='{$monitor_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'124','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
      $data_state = 1;
      if($type_id=='4' || $type_id=='3'){
        $sql_monitor ="SELECT monitor_id, group_id, device_id, type_id, is_min, min_value, is_max, max_value, is_input_digi, is_digital_zero, is_line, input_line, is_email, input_email, is_sms, input_sms, createtime, updatetime, status, list_time_of_work,is_condition, config_value_1, config_value_2, config_value_3, config_value_4,datax_id,datax_value
                      FROM page_data_manage_monitor  WHERE monitor_id='{$monitor_id}' ORDER BY monitor_id DESC,sort ASC;";
                      // echo $sql_monitor;exit;
              $rs_monitor = select($sql_monitor,$db);


              $sql_sel_datax  = "SELECT datax_id, datax_name, create_time, update_time, sort, status
                            FROM page_data_manage_datax WHERE status='1' ORDER BY datax_id ASC,sort ASC;";
              $rs_sel_datax   = select($sql_sel_datax,$db);
              $array_datax    = array();
              if(count($rs_sel_datax)>0){
                for($j=0;$j<count($rs_sel_datax);$j++){
                  $array_datax[$rs_sel_datax[$j]->datax_id] = $rs_sel_datax[$j]->datax_name;
                }
              }

                    $sql_get_datax_from_volte_censor = "SELECT a.name as device,a.location as group,a.\"".strtoupper($array_datax[$rs_monitor[0]->datax_id])."\" as datax
                                                            FROM volte_censor a
                                                            LEFT JOIN page_data_manage_device b ON a.name = b.divice_name
                                                            LEFT JOIN page_data_manage_group c ON a.location = c.value_map_volte_censor
                                                          WHERE b.device_id='{$rs_monitor[0]->device_id}' AND c.group_id ='{$rs_monitor[0]->group_id}'
                                                          ORDER BY a.id DESC LIMIT 1;
                                  ";
                  $rs_get_datax_from_volte_censor = select($sql_get_datax_from_volte_censor,$db);
                if(count($rs_get_datax_from_volte_censor)=='1' && $rs_get_datax_from_volte_censor[0]->datax =='1'){
                  $data_state = 1;
                }else{
                  $data_state = 0;
                }

                if($type_id=='3' && count($rs_get_datax_from_volte_censor)=='1' && $rs_get_datax_from_volte_censor[0]->datax > 0){
                  $data_state = $rs_get_datax_from_volte_censor[0]->datax;
                }
      }

      $msg = "success";
      $respose = array('ret'=>'101','msg' => $msg,"data_state" => $data_state);
      echo json_encode($respose);
      pg_close($db);
      exit;
    }else if($page1_sql_type == '7'){
      $datax_id = "";
      if(isset($_POST['datax_id']) && $_POST['datax_id'] != ""){
        $datax_id = $_POST['datax_id'];
      }else{
        $msg = "Param datax_id is NULL";
        $respose = array('ret'=>'125','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      
      $update_monitor = "UPDATE page_data_manage_monitor
                          SET datax_id = '{$datax_id}',updatetime = now()
                          WHERE monitor_id='{$monitor_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'126','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($page1_sql_type == '8'){
      $update_monitor = "DELETE FROM page_data_manage_monitor
	                      WHERE monitor_id='{$monitor_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }else if($page1_sql_type == '9'){
      $monitor_name_input = "";
      if(isset($_POST['monitor_name_input'])){
        $monitor_name_input = $_POST['monitor_name_input'];
      }else{
        $msg = "Param monitor_name_input is NULL";
        $respose = array('ret'=>'1255','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      // if($monitor_name_input==""){
      //   $monitor_name_input = "-";
      // }
      $update_monitor = "UPDATE page_data_manage_monitor
                          SET monitor_name = '{$monitor_name_input}',updatetime = now()
                          WHERE monitor_id='{$monitor_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'1266','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }

    $msg = "success";
    $respose = array('ret'=>'101','msg' => $msg);
    echo json_encode($respose);

//  $sql ="INSERT INTO page_data_manage_monitor(group_id, device_id, type_id)
// 	VALUES ('1', '1', '1');";

//  $rs = execute($sql,$db);
//  if($rs){
//   echo "Success";
//   exit;
//  }else{
//   echo "Fail!";
//   exit;
//  }
pg_close($db);
?>  