<?php
 include_once("includes/fn/pg_connect.php");
 
 $db = pg_connect( "$host $port $dbname $credentials"  );

 if(!$db) {
     $msg = "Error : Unable to open database";
     $respose = array('ret'=>'100','msg'=>$msg);
     echo json_encode($respose);
     exit;
 }
    $get_realtime_type = "";
    if(isset($_POST['get_realtime_type']) && $_POST['get_realtime_type'] !=""){
      $get_realtime_type = $_POST['get_realtime_type'];
    }else{
      $msg = "Param get_realtime_type is NULL";
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
    
    if($get_realtime_type == '1'){
      
      $data_realtime = 0;
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
                                                          WHERE b.device_id='{$rs_monitor[0]->device_id}' AND c.group_id ='{$rs_monitor[0]->group_id}' AND a.\"".strtoupper($array_datax[$rs_monitor[0]->datax_id])."\" IS NOT NULL
                                                          ORDER BY a.id DESC LIMIT 1;
                                  ";
                  $rs_get_datax_from_volte_censor = select($sql_get_datax_from_volte_censor,$db);
                if(count($rs_get_datax_from_volte_censor)=='1' && $rs_get_datax_from_volte_censor[0]->datax !=""){
                  $data_realtime = $rs_get_datax_from_volte_censor[0]->datax;
                }
                $msg = "success";
                $respose = array('ret'=>'101','msg' => $msg,"data_realtime"=>$data_realtime);
                echo json_encode($respose);
    }

    

pg_close($db);
?>  