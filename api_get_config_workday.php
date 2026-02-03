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
    
 $monitor_id = '';

 if(isset($_POST['monitor_id']) && $_POST['monitor_id'] !=''){
    $monitor_id = $_POST['monitor_id'];
 }else if(isset($_GET['monitor_id']) && $_GET['monitor_id'] !=''){
    $monitor_id = $_GET['monitor_id'];
 }
 if($monitor_id == ''){
    $msg = "Param monitor_id is null";
    $respose = array('ret'=>'201','msg'=>$msg);
    echo json_encode($respose);
    exit;
 }

    $sql_monitor = "SELECT monitor_id, group_id, device_id, type_id, is_min, min_value, 
                      is_max, max_value, is_input_digi, is_digital_zero, 
                      is_line, input_line, is_email, input_email, is_sms, 
                      input_sms, createtime, updatetime, status, 
                      list_time_of_work, sort, is_condition, is_analog_min_work, 
                      is_analog_max_work, is_digital_work, 
                      datax_id, datax_value, monitor_name
                        FROM page_data_manage_monitor WHERE monitor_id = '{$monitor_id}' ;";
    $rs_monitor = select($sql_monitor,$db);
    if(count($rs_monitor)==0){
        $msg = "Cannot find data config (count 0 rows)";
        $respose = array('ret'=>'202','msg'=>$msg);
        echo json_encode($respose);
        exit;
    }

    $sql_time_of_work = "SELECT TO_CHAR(start_work,'HH24:MI') as start_work, TO_CHAR(end_work,'HH24:MI') as end_work FROM page_data_manage_monitor_sub WHERE status='1' AND monitor_id ='{$monitor_id}' ORDER BY monitor_sub_id ASC,updatetime ASC;";
    $rs_time_of_work   = select($sql_time_of_work,$db);


    $list_time_of_work      = $rs_monitor[0]->list_time_of_work;
    $more_data = array();
    
    $array_newListTimeOfWork = array();
      if($list_time_of_work!=""){
        $array_newListTimeOfWork = explode(",",$list_time_of_work);
      }
      $time_of_work_monday = "NO";
      $time_of_work_tuesday = "NO";
      $time_of_work_wenday = "NO";
      $time_of_work_thursday = "NO";
      $time_of_work_friday = "NO";
      $time_of_work_saturday = "NO";
      $time_of_work_sunday = "NO";
      if(in_array('1', $array_newListTimeOfWork)){$time_of_work_monday = "YES";}
      if(in_array('2', $array_newListTimeOfWork)){$time_of_work_tuesday = "YES";}
      if(in_array('3', $array_newListTimeOfWork)){$time_of_work_wenday = "YES";}
      if(in_array('4', $array_newListTimeOfWork)){$time_of_work_thursday = "YES";}
      if(in_array('5', $array_newListTimeOfWork)){$time_of_work_friday = "YES";}
      if(in_array('6', $array_newListTimeOfWork)){$time_of_work_saturday = "YES";}
      if(in_array('0', $array_newListTimeOfWork)){$time_of_work_sunday = "YES";}
      
      $array_workdays = array("data_workdays"=> 
                          array(
                            "day_of_work_monday"=> $time_of_work_monday,
                            "day_of_work_tuesday"=> $time_of_work_tuesday,
                            "day_of_work_wenday"=> $time_of_work_wenday,
                            "day_of_work_thursday"=> $time_of_work_thursday,
                            "day_of_work_friday"=> $time_of_work_friday,
                            "day_of_work_saturday"=> $time_of_work_saturday,
                            "day_of_work_sunday"=> $time_of_work_sunday
                      ));

      array_push($more_data,$array_workdays);
      // $array_subtime = array();
      if(count($rs_time_of_work)>0){
        // for($i=0;$i<count($rs_time_of_work);$i++){
        //   // array_push($array_subtime);
        //   // $rs_time_of_work[$i]->start_work;
        //   // $rs_time_of_work[$i]->end_work;
        // }
        $array_worktimes = array("data_worktimes"=> $rs_time_of_work);
      }else{
        $array_worktimes = array("data_worktimes"=> array());
      }
      
      array_push($more_data,$array_worktimes);


    $msg = "success";
    $respose = array('ret'=>'101','msg' => $msg, 'more_data' => $more_data);
    echo json_encode($respose);

pg_close($db);
?>  