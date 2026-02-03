<?php
 include_once("includes/fn/pg_connect.php");
 
 $db = pg_connect( "$host $port $dbname $credentials"  );

 if(!$db) {
     $msg = "Error : Unable to open database";
     $respose = array('ret'=>'100','msg'=>$msg);
     echo json_encode($respose);
     exit;
 }
    $data_sim_type = "";
    if(isset($_POST['data_sim_type']) && $_POST['data_sim_type'] !=""){
      $data_sim_type = $_POST['data_sim_type'];
    }else{
      $msg = "Param data_sim_type is NULL";
      $respose = array('ret'=>'102','msg'=>$msg);
      echo json_encode($respose);
      exit;
    }
    
    if($data_sim_type == '1'){
      $group_id = "";
      if(isset($_POST['group_id']) && $_POST['group_id'] !=""){
        $group_id = $_POST['group_id'];
      }else{
        $msg = "Param group_id is NULL";
        $respose = array('ret'=>'105','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $type_id = "";
      if(isset($_POST['type_id']) && $_POST['type_id'] !=""){
        $type_id = $_POST['type_id'];
      }else{
        $msg = "Param type_id is NULL";
        $respose = array('ret'=>'106','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $device_id = "";
      if(isset($_POST['device_id']) && $_POST['device_id'] !=""){
        $device_id = $_POST['device_id'];
      }else{
        $msg = "Param device_id is NULL";
        $respose = array('ret'=>'106','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $datax_id = "";
      if(isset($_POST['datax_id']) && $_POST['datax_id'] !=""){
        $datax_id = $_POST['datax_id'];
      }else{
        $msg = "Param datax_id is NULL";
        $respose = array('ret'=>'107','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
      $data_value = "";
      if(isset($_POST['data_value']) && $_POST['data_value'] !=""){
        $data_value = $_POST['data_value'];
      }else{
        $msg = "Param data_value is NULL";
        $respose = array('ret'=>'108','msg'=>$msg);
        echo json_encode($respose);
        exit;
      }
     
      $sql_device = "SELECT device_id, divice_name FROM page_data_manage_device where device_id='{$device_id}';";
      $rs_device   = select($sql_device,$db);
      if(count($rs_device)!='1'){
        $msg = "device_id is wrong".$sql_device;
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

      $sql_group = "SELECT group_id, group_name, value_map_volte_censor FROM page_data_manage_group WHERE group_id='{$group_id}';";
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
                      ('4', '4', '{$divice_name}', '{$value_map_volte_censor}', '".date('Y-m-d')."', now(), now(), '0', '1', '0', '0', '0', '{$data_value}');";
      $rs_insert_volte = execute($insert_volte,$db);
      if(!$rs_insert_volte){
        $msg = "insert volte fail";
        $respose = array('ret'=>'108','msg' => $msg);
        echo json_encode($respose);
        exit;
      }


    }

    $msg = "success";
    $respose = array('ret'=>'101','msg' => $msg);
    echo json_encode($respose);

pg_close($db);
?>  