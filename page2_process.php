<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
  // echo "<pre>";
  // print_r($_POST);
  if(isset($_POST['monitor_id'])){
  include_once("includes/fn/pg_connect.php");
  $db = pg_connect( "$host $port $dbname $credentials"  );
  if(!$db) {
      echo "Error : Unable to open database\n";
      exit;
  }
$monitor_id                 = isset($_POST['monitor_id'])? $_POST['monitor_id'] : "";
$analog_min                 = isset($_POST['analog_min'])? $_POST['analog_min'] : 0;
$analog_min_number          = isset($_POST['analog_min_number'])? $_POST['analog_min_number'] : 0;
$analog_max                 = isset($_POST['analog_max'])? $_POST['analog_max'] : 0;
$analog_min_work                 = isset($_POST['analog_min_work'])? $_POST['analog_min_work'] : 0;
$analog_max_work                 = isset($_POST['analog_max_work'])? $_POST['analog_max_work'] : 0;
$analog_max_number          = $_POST['analog_max_number'];
$analog_real_value          = $_POST['analog_real_value'];
$digital_real_value         = $_POST['digital_real_value'];
$time_of_work_day           = isset($_POST['time_of_work_day'])? $_POST['time_of_work_day'] : array();
$digi_input_value           = isset($_POST['digi_input_value'])? $_POST['digi_input_value'] : 0;
$is_digital_work            = isset($_POST['is_digital_work'])? $_POST['is_digital_work'] : 0;


$time_of_work_dayNew        = "";
if(count($time_of_work_day)>0){
  $time_of_work_dayNew = join(",",$time_of_work_day);
}
$start_time                 = $_POST['start_time'];//] => Array
$end_time                   = $_POST['end_time'];//] => Array
$noti_email                 = isset($_POST['noti_email'])? $_POST['noti_email'] : 0;
$noti_sms                   = isset($_POST['noti_sms'])? $_POST['noti_sms'] : 0;
$noti_line_input            = $_POST['noti_line_input'];
$noti_email_input           = $_POST['noti_email_input'];
$noti_sms_input             = $_POST['noti_sms_input'];
// echo $time_of_work_dayNew;
    $sql_monitor ="SELECT monitor_id, group_id, device_id, type_id, is_min, min_value, is_max, max_value, is_input_digi, is_digital_zero, is_line, input_line, is_email, input_email, is_sms, input_sms, createtime, updatetime, status, list_time_of_work,is_condition, config_value_1, config_value_2, config_value_3, config_value_4,is_analog_min_work,is_analog_max_work
    FROM page_data_manage_monitor  WHERE status='1' AND monitor_id='{$monitor_id}' ORDER BY monitor_id DESC,sort ASC;";
    // echo $sql_monitor;exit;
    $rs_monitor = select($sql_monitor,$db);
    if(count($rs_monitor)==0){
      echo "<br><br><br><br><br><br><br><center><b>Cannot find Monitor ID : ".$monitor_id."</b><center><br><br><br><br><br><br><br>";
      echo '<a href="page1.php">
                  <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                </a>';
      exit;
    }
    $monitor_id             = $rs_monitor[0]->monitor_id;
    $group_id               = $rs_monitor[0]->group_id;
    $device_id              = $rs_monitor[0]->device_id;
    $type_id                = $rs_monitor[0]->type_id;


    $sql_sel_type  = "SELECT type_id, type_name, image_src, data_col_name,state,operation
    FROM public.page_data_manage_type WHERE type_id='{$type_id}' ORDER BY type_id ASC,sort ASC;";
    $rs_sel_type   = select($sql_sel_type,$db);
    if(count($rs_sel_type) == 0){
      echo "<br><br><br><br><br><br><br><center><b>Cannot find Type ID : ".$type_id."</b><center><br><br><br><br><br><br><br>";
      echo '<a href="page1.php">
      <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
      </a>';
      exit;
    }
    $type_name             = $rs_sel_type[0]->type_name;
    $data_col_name         = $rs_sel_type[0]->data_col_name;
    $type_state            = $rs_sel_type[0]->state;  // A=analog, D=digital
    $type_operation        = $rs_sel_type[0]->operation; //IN = input, OUT = output

    $SQL_update_analog     = "  ";
    $SQL_update_digital    = "  ";
    if($type_state=='A'){
      $SQL_update_analog     = " ,is_min='{$analog_min}', min_value='{$analog_min_number}', 
                                  is_max='{$analog_max}', max_value='{$analog_max_number}',  
                                  is_analog_min_work='{$analog_min_work}', 
                                  is_analog_max_work='{$analog_max_work}' ";
    }else if($type_state=='D'){
      $SQL_update_digital    = "  ,is_digital_zero='{$digi_input_value}', 
                                  is_digital_work='{$is_digital_work}' ";
    }

    $noti_line = 0;
    $noti_email = 0;
    $noti_sms = 0;
    if(isset($_POST['noti_line'])){
      $noti_line = 1;
    }
    if(isset($_POST['noti_email'])){
      $noti_email = 1;
    }
    if(isset($_POST['noti_sms'])){
      $noti_sms = 1;
    }



    $update_monitor ="UPDATE page_data_manage_monitor   SET    is_line={$noti_line}, 
                                                input_line='{$noti_line_input}', 
                                                is_email='{$noti_email}', 
                                                input_email='{$noti_email_input}', 
                                                is_sms='{$noti_sms}', 
                                                input_sms='{$noti_sms_input}', 
                                                updatetime=Now(), 
                                                list_time_of_work='{$time_of_work_dayNew}'  
                                                {$SQL_update_analog} 
                                                {$SQL_update_digital}
                                                WHERE monitor_id='{$monitor_id}';";
  
    // echo $update_monitor;exit;
    $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'114','msg' => $msg);
        echo json_encode($respose);
        echo '<a href="page1.php">
                  <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                </a>';
        exit;
      }
      if(count($start_time)>0){
       for($i=0;$i<count($start_time);$i++){
        $start  = $start_time[$i].":00";
        $end    = $end_time[$i].":00";
        
        if(!($start=='00:00:00' && $end=='00:00:00')){  
          // echo $start."|".$end."<br>";exit;
          $chk_insert_monit_sub     = "SELECT monitor_sub_id,monitor_id, start_work, end_work
                                    FROM page_data_manage_monitor_sub WHERE start_work='{$start}' AND  end_work='{$end}' AND monitor_id='{$monitor_id}';";
          $rs_chk_insert_monit_sub = select($chk_insert_monit_sub,$db);
          if(count($rs_chk_insert_monit_sub)==0){
            $insert_sub ="INSERT INTO page_data_manage_monitor_sub(
                          monitor_id, start_work, end_work, createtime, updatetime)
                          VALUES ( '{$monitor_id}', '{$start}', '{$end}',Now(), Now());";
                          // echo $insert_sub;exit;
            $res_insert_sub = execute($insert_sub,$db);
            if(!$res_insert_sub){
              $msg = "Insert monitor sub fail";
              $respose = array('ret'=>'114','msg' => $msg);
              echo json_encode($respose);
              exit;
            }
          }else{
            $update_sub ="UPDATE page_data_manage_monitor_sub
                          SET start_work='{$start}', end_work='{$end}', updatetime=Now(), status='1'
                          WHERE monitor_id='{$monitor_id}' AND end_work='{$end}' AND start_work='{$start}';";
            $res_update_sub = execute($update_sub,$db);
            if(!$res_update_sub){
              $msg = "Update monitor sub fail";
              $respose = array('ret'=>'115','msg' => $msg);
              echo json_encode($respose);
              exit;
            }
          }
        }
       } 
      }
      // exit;
      header("Location: page2.php?monitor_id=".$monitor_id);
  }else{
    echo "<br><br><br><br><br><br><br><center><b>Something wrong </b><center><br><br><br><br><br><br><br>";
      echo '<a href="page1.php">
                  <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                </a>';
      exit;
  }
?>