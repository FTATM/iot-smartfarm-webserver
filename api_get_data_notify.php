<?php
 include_once("includes/fn/pg_connect.php");
 
 $db = pg_connect( "$host $port $dbname $credentials"  );
 header('Content-Type: application/json; charset=utf-8');
 if(!$db) {
     $msg = "Error : Unable to open database";
     $respose = array('ret'=>'100','msg'=>$msg);
     echo json_encode($respose);
     exit;
 }
    


    /*
      is_condition = Active setting [Page1]
      type_id = 1->Analog_out, 2->Digital_out, 3->Analog_in, 4->Digital_in
      if(1 or 3){
        check analog "is_analog_min_work" ,"is_analog_max_work"
        value compare "min_value", "max_value"
      }else if(2 or 4){
        check digital "is_digital_work"
        value compare "is_input_digi"
      }

      datax_id = map Table "Volte_censor" by join datax_name for get Real Value

	*/

    $sql_monitor = "SELECT monitor_id, group_id, device_id, type_id, is_min, min_value, 
                      is_max, max_value, is_input_digi, is_digital_zero, 
                      is_line, input_line, is_email, input_email, is_sms, 
                      input_sms, createtime, updatetime, status, 
                      list_time_of_work, sort, is_condition, is_analog_min_work, 
                      is_analog_max_work, is_digital_work, 
                      datax_id, datax_value, monitor_name
                        FROM page_data_manage_monitor WHERE status = '1' ;";
    $rs_monitor = select($sql_monitor,$db);
    $data_alert = array();
    
    $sql_sel_datax  = "SELECT datax_id, datax_name, create_time, update_time, sort, status
                            FROM page_data_manage_datax WHERE status='1' ORDER BY datax_id ASC,sort ASC;";
              $rs_sel_datax   = select($sql_sel_datax,$db);
              $array_datax    = array();
              if(count($rs_sel_datax)>0){
                for($j=0;$j<count($rs_sel_datax);$j++){
                  $array_datax[$rs_sel_datax[$j]->datax_id] = $rs_sel_datax[$j]->datax_name;
                }
              }
    if(count($rs_monitor)>0){
      for($i=0;$i<count($rs_monitor);$i++){
          $msg_alert  = "<br>❗❗❗❗";
          $msg_alert .= "<br>Monitor ID : ".$rs_monitor[$i]->monitor_id;
          $msg_alert .= "<br>Monitor Name : ".$rs_monitor[$i]->monitor_name;
          $is_alert = "NO";
          $is_line_alert = "NO";
          $is_email_alert = "NO";
          $is_sms_alert = "NO";
          $line_alert_value = "";
          $email_alert_value = "";
          $sms_alert_value = "";
          if($rs_monitor[$i]->is_line=='1'){
            $is_line_alert = "YES";
            $line_alert_value = $rs_monitor[$i]->input_line;
          }
          if($rs_monitor[$i]->is_email=='1'){
            $is_email_alert = "YES";
            $email_alert_value = $rs_monitor[$i]->input_email;
          }
          if($rs_monitor[$i]->is_sms=='1'){
            $is_sms_alert = "YES";
            $sms_alert_value = $rs_monitor[$i]->input_sms;
          }

          if($rs_monitor[$i]->is_condition=='1'){//Check Active setting [Page1]
              $datax_compare = 0;
              $write_date = '0000-00-00 00:00:00';
              $sql_get_datax_from_volte_censor = "SELECT a.name as device,a.location as group,a.\"".strtoupper($array_datax[$rs_monitor[$i]->datax_id])."\" as datax,a.write_date
                                                              FROM volte_censor a
                                                              LEFT JOIN page_data_manage_device b ON a.name = b.divice_name
                                                              LEFT JOIN page_data_manage_group c ON a.location = c.value_map_volte_censor
                                                            WHERE b.device_id='{$rs_monitor[$i]->device_id}' AND c.group_id ='{$rs_monitor[$i]->group_id}'
                                                            ORDER BY a.id DESC LIMIT 1;";
            $rs_get_datax_from_volte_censor = select($sql_get_datax_from_volte_censor,$db);
            if(count($rs_get_datax_from_volte_censor)==1){
              $datax_compare  = $rs_get_datax_from_volte_censor[0]->datax;
              $write_date     = $rs_get_datax_from_volte_censor[0]->write_date;
            }
            // if($rs_monitor[$i]->monitor_id=='4'){
            //   echo $sql_get_datax_from_volte_censor;
            //   exit;
            // }
            if($rs_monitor[$i]->type_id == '1' || $rs_monitor[$i]->type_id == '3'){//Type Analog
              if($rs_monitor[$i]->is_analog_min_work=='1'){// if enable check min value
                if($rs_monitor[$i]->min_value > $datax_compare){
                  $is_alert   = "YES";
                  $msg_alert .= "<br>Real Value = ".number_format($datax_compare);
                  $msg_alert .= "<br>🔻Less than MIN Value = ".number_format($rs_monitor[$i]->min_value);
                }
              }
              if($rs_monitor[$i]->is_analog_max_work=='1'){// if enable check max value
                if($rs_monitor[$i]->max_value < $datax_compare){
                  $is_alert   = "YES";
                  $msg_alert .= "<br>Real Value = ".number_format($datax_compare);
                  $msg_alert .= "<br>🔺More than MAX Value = ".number_format($rs_monitor[$i]->max_value);
                }
              }
            }else if($rs_monitor[$i]->type_id == '2' || $rs_monitor[$i]->type_id == '4'){//Type Digital
              if($rs_monitor[$i]->is_digital_work=='1'){// if enable check value
                if($rs_monitor[$i]->is_input_digi != $datax_compare){
                  $is_alert   = "YES";
                  $realValueStatus = "🔴OFF";
                  $compareValueStatus = "🔴OFF";
                  if($datax_compare=='1'){
                    $realValueStatus = "🟢ON";
                  }
                  if($rs_monitor[$i]->is_input_digi=='1'){
                    $compareValueStatus = "🟢ON";
                  }
                  if($datax_compare != '0' && $datax_compare != '1'){
                    $realValueStatus = $datax_compare;
                  }

                  $msg_alert .= "<br>Real Value Status is '".$realValueStatus."'";
                  $msg_alert .= "<br>Compare Value Status is '".$compareValueStatus."'";
                }
              }
            }
            $array_detail = array(
                            "is_alert" => $is_alert,
                            "msg_alert" => $msg_alert,
                            "is_line_alert" => $is_line_alert,
                            "line_alert_value" => $line_alert_value,
                            "is_email_alert" => $is_email_alert,
                            "email_alert_value" => $email_alert_value,
                            "is_sms_alert" => $is_sms_alert,
                            "sms_alert_value" => $sms_alert_value
            );
            if($is_alert=='YES'){
             array_push($data_alert,$array_detail);
            }
            
          }
      }
    }else{
      $msg = "Select data monitor fail";
      $respose = array('ret'=>'102','msg' => $msg, 'data_alert' => $data_alert);
      echo json_encode($respose);
    }

    $msg = "success";
    $respose = array('ret'=>'101','msg' => $msg, 'data_alert' => $data_alert);
    echo json_encode($respose);

pg_close($db);
?>  