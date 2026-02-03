<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page 2</title>
    
    <link rel="stylesheet" href="includes/css/style.css">
    <!-- <link rel="stylesheet" href="https://fgelinas.com/code/timepicker/include/ui-1.10.0/ui-lightness/jquery-ui-1.10.0.custom.min.css" type="text/css">
    <link rel="stylesheet" href="https://fgelinas.com/code/timepicker/jquery.ui.timepicker.css?v=0.3.3" type="text/css"> -->
    <style>
      #digital_sel{
        display:none;
      }
    </style>
    
</head>
<body>
  <?php
  //nav bar
    include_once("nav_bar.php");
  ?>
  <?php
  
    $arrayData = array(

    ); 
    if(!isset($_GET['monitor_id']) || $_GET['monitor_id']==''){
      echo "<br><br><br><br><br><br><br><center><b>You came in the wrong way.</b><center><br><br><br><br><br><br><br>";
      echo '<a href="page1.php">
                  <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                </a>';
      exit;
    }

  include_once("includes/fn/pg_connect.php");
  $db = pg_connect( "$host $port $dbname $credentials"  );
  if(!$db) {
      echo "Error : Unable to open database\n";
      exit;
  }
      $monitor_id = $_GET['monitor_id'];
      $sql_monitor ="SELECT monitor_id, group_id, device_id, type_id, is_min, min_value, is_max, max_value, is_input_digi, is_digital_zero, is_line, input_line, is_email, input_email, is_sms, input_sms, createtime, updatetime, status, list_time_of_work,is_condition, config_value_1, config_value_2, config_value_3, config_value_4,is_analog_min_work,is_analog_max_work,datax_id,datax_value,monitor_name,is_digital_work
                FROM page_data_manage_monitor  WHERE status='1' AND monitor_id='{$monitor_id}' ORDER BY monitor_id DESC,sort ASC;";
                // echo $sql_monitor;exit;
      $rs_monitor = select($sql_monitor,$db);

      // echo "<pre>";
      // print_r($rs_monitor);

      if(count($rs_monitor)==0){
        echo "<br><br><br><br><br><br><br><center><b>Cannot find Monitor ID : ".$monitor_id."</b><center><br><br><br><br><br><br><br>";
        echo '<a href="page1.php">
                    <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                  </a>';
        exit;
      }
      $monitor_id             = $rs_monitor[0]->monitor_id;
      $monitor_name           = $rs_monitor[0]->monitor_name==""?"-":$rs_monitor[0]->monitor_name;
      $group_id               = $rs_monitor[0]->group_id;
      $device_id              = $rs_monitor[0]->device_id;
      $type_id                = $rs_monitor[0]->type_id;
      $datax_id               = $rs_monitor[0]->datax_id;
      $is_min                 = $rs_monitor[0]->is_min;
      $min_value              = $rs_monitor[0]->min_value;
      $is_max                 = $rs_monitor[0]->is_max;
      $max_value              = $rs_monitor[0]->max_value;
      $is_input_digi          = $rs_monitor[0]->is_input_digi;
      $is_digital_zero        = $rs_monitor[0]->is_digital_zero;
      $is_line                = $rs_monitor[0]->is_line;
      $input_line             = $rs_monitor[0]->input_line;
      $is_email               = $rs_monitor[0]->is_email;
      $input_email            = $rs_monitor[0]->input_email;
      $is_sms                 = $rs_monitor[0]->is_sms;
      $input_sms              = $rs_monitor[0]->input_sms;
      $status                 = $rs_monitor[0]->status;
      $list_time_of_work      = $rs_monitor[0]->list_time_of_work; // date('w'); w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
      $is_condition           = $rs_monitor[0]->is_condition;
      $config_value_1         = $rs_monitor[0]->config_value_1;
      $config_value_2         = $rs_monitor[0]->config_value_2;
      $config_value_3         = $rs_monitor[0]->config_value_3;
      $config_value_4         = $rs_monitor[0]->config_value_4;
      $is_analog_min_work     = $rs_monitor[0]->is_analog_min_work;
      $is_analog_max_work     = $rs_monitor[0]->is_analog_max_work;
      $is_digital_work        = $rs_monitor[0]->is_digital_work;
      $array_newListTimeOfWork = array();
      if($list_time_of_work!=""){
        $array_newListTimeOfWork = explode(",",$list_time_of_work);
      }
      


      $sql_sel_group          = "SELECT group_id, group_name,value_map_volte_censor
	                        FROM page_data_manage_group WHERE group_id='{$group_id}' ORDER BY group_id ASC,sort ASC;";
      $rs_sel_group           = select($sql_sel_group,$db);
      if(count($rs_sel_group) == 0){
        echo "<br><br><br><br><br><br><br><center><b>Cannot find Group ID : ".$group_id."</b><center><br><br><br><br><br><br><br>";
        echo '<a href="page1.php">
                    <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                  </a>';
        exit;
      }
      $group_name             = $rs_sel_group[0]->group_name;
      $value_map_volte_censor = $rs_sel_group[0]->value_map_volte_censor;
      
      $sql_sel_device  = "SELECT device_id, divice_name
	                        FROM page_data_manage_device WHERE  device_id='{$device_id}' ORDER BY device_id ASC,sort ASC;";
      $rs_sel_device   = select($sql_sel_device,$db);
      if(count($rs_sel_device) == 0){
        echo "<br><br><br><br><br><br><br><center><b>Cannot find Device ID : ".$device_id."</b><center><br><br><br><br><br><br><br>";
        echo '<a href="page1.php">
                    <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                  </a>';
        exit;
      }
      $device_name             = $rs_sel_device[0]->divice_name;
      $sql_sel_type  = "SELECT type_id, type_name, image_src, data_col_name,state,operation
	                        FROM page_data_manage_type WHERE type_id='{$type_id}' ORDER BY type_id ASC,sort ASC;";
      $rs_sel_type   = select($sql_sel_type,$db);
      if(count($rs_sel_type) == 0){
        echo "<br><br><br><br><br><br><br><center><b>Cannot find Type ID : ".$type_id."</b><center><br><br><br><br><br><br><br>";
        echo '<a href="page1.php">
                    <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                  </a>';
        exit;
      }
      $sql_sel_datax  = "SELECT datax_id, datax_name, create_time, update_time, sort, status
	                          FROM page_data_manage_datax WHERE datax_id='{$datax_id}' ORDER BY datax_id ASC,sort ASC;";
      $rs_sel_datax   = select($sql_sel_datax,$db);
      if(count($rs_sel_datax) == 0){
        echo "<br><br><br><br><br><br><br><center><b>Cannot find DATA ID : ".$datax_id."</b><center><br><br><br><br><br><br><br>";
        echo '<a href="page1.php">
                    <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                  </a>';
        exit;
      }

      $sql_time_of_work = "SELECT monitor_sub_id,monitor_id, TO_CHAR(start_work,'HH24:MI') as start_work, TO_CHAR(end_work,'HH24:MI') as end_work FROM page_data_manage_monitor_sub WHERE status='1' AND monitor_id ='{$monitor_id}' ORDER BY monitor_sub_id ASC,updatetime ASC;";
      $rs_time_of_work   = select($sql_time_of_work,$db);


      $type_name             = $rs_sel_type[0]->type_name;
      $data_col_name         = $rs_sel_type[0]->data_col_name;
      $type_state            = $rs_sel_type[0]->state;  // A=analog, D=digital
      $type_operation        = $rs_sel_type[0]->operation; //IN = input, OUT = output
      $datax_name            = $rs_sel_datax[0]->datax_name;

      // $sql_real_value       = "SELECT  \"$data_col_name\" as data_real FROM volte_censor WHERE name ='{$device_name}' and location ='{$value_map_volte_censor}' ORDER BY create_date desc limit 1;";
      // // echo $sql_real_value;exit;
      // $rs_real_value        = select($sql_real_value,$db);


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

        $analog_real_value      = 0;
        $digital_real_value     = 0;
      if(count($rs_get_datax_from_volte_censor)=='1'){
        $analog_real_value      = $rs_get_datax_from_volte_censor[0]->datax;
        $digital_real_value     = $rs_get_datax_from_volte_censor[0]->datax;
      }
      
  ?>
  <div class="bg-background text-foreground p-4">
  <p class="text-sm text-muted-foreground mb-4">Monitor ID : <?php echo $monitor_id;?></p>
  <p class="text-sm text-muted-foreground mb-4">Monitor Name : <?php echo $monitor_name;?></p>
    <h2 class="text-2xl font-bold mb-4">Page2</h2>
    <form class="flex flex-col space-y-4" METHOD="POST" ACTION="page2_process.php">
      <input type="hidden" name="monitor_id" id="monitor_id" value="<?php echo $monitor_id;?>" />
      <label for="group" class="text-sm">1. Group :</label>
      <input type="text" id="group" name="group" placeholder="Enter your Group" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
      invalid:border-pink-500 invalid:text-pink-600
      focus:invalid:border-pink-500 focus:invalid:ring-pink-500" value="<?php echo $group_name;?>" disabled />
      <hr>
      <label for="device" class="text-sm">2. Device :</label>
      <input type="text" id="device" name="device" placeholder="Enter your Device" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
      invalid:border-pink-500 invalid:text-pink-600
      focus:invalid:border-pink-500 focus:invalid:ring-pink-500" value="<?php echo $device_name;?>" disabled />
      <label for="data" class="text-sm">3. Type :</label>
      <input type="text" id="data" name="data" placeholder="Enter your Data" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
      invalid:border-pink-500 invalid:text-pink-600
      focus:invalid:border-pink-500 focus:invalid:ring-pink-500" value="<?php echo $type_name;?>" disabled />
      <label for="data" class="text-sm">4. Data :</label>
      <input type="text" id="data" name="data" placeholder="Enter your Data" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
      invalid:border-pink-500 invalid:text-pink-600
      focus:invalid:border-pink-500 focus:invalid:ring-pink-500" value="<?php echo $datax_name;?>" disabled />
      <hr>
      <h4>Data Analog Or Digital :</h4>
      <label for="analog"><input type="radio" id="analog" name="sw_ana_digi" value="analog" checked> Analog </label>
      <label for="digital"><input type="radio" id="digital" name="sw_ana_digi" value="digital"> Digital </label>
      <hr>
      <div id="analog_sel">
      <h4>3.1 Analog :</h4>
        <div id="ana_min">
          <label for="analog_min"><input type="checkbox" id="analog_min" name="analog_min" <?php if($is_min=='1'){echo "checked";}?> value="1"> Min </label>
          <br>
          <label for="analog_min_number" class="text-sm">Analog min number :</label>
          <input type="text" id="analog_min_number" name="analog_min_number" placeholder="Enter min number" class="p-2 border border-input rounded-md focus:outline-none focus:ring ring-primary" value="<?php echo $min_value;?>" />
          <br>
            <label class="inline-flex items-center cursor-pointer">
                  <span class="text-sm font-medium text-gray-900 dark:text-gray-300">สถานะทำงาน </span>
                  <input type="checkbox" value="<?php echo $is_analog_min_work;?>"  id="analog_min_work" name="analog_min_work" class="sr-only peer" <?php if($is_analog_min_work=='1'){echo "checked";} ?> onclick="active_set_change('analog_min_work')" />
                  &nbsp;&nbsp;
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    &nbsp;&nbsp;<span class="text-sm font-medium text-gray-900 dark:text-gray-300">Off/On</span>
            </label>
        </div>
        <hr>
        <div id="ana_max">
          <label for="analog_max"><input type="checkbox" id="analog_max" name="analog_max" <?php if($is_max=='1'){echo "checked";}?> value="1"> Max </label>
          <br>
          <label for="analog_max_number" class="text-sm">Analog max number :</label>
          <input type="text" id="analog_max_number" name="analog_max_number" placeholder="Enter max number" class="p-2 border border-input rounded-md focus:outline-none focus:ring ring-primary" value="<?php echo $max_value;?>" />
          <br>
          <label class="inline-flex items-center cursor-pointer">
                  <span class="text-sm font-medium text-gray-900 dark:text-gray-300">สถานะทำงาน </span>
                  <input type="checkbox" value="<?php echo $is_analog_max_work;?>"  id="analog_max_work" name="analog_max_work"  class="sr-only peer" <?php if($is_analog_max_work=='1'){echo "checked";} ?> onclick="active_set_change('analog_max_work')" />
                  &nbsp;&nbsp;
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    &nbsp;&nbsp;<span class="text-sm font-medium text-gray-900 dark:text-gray-300">Off/On</span>
          </label>
        </div>
        <label for="analog_real_value" class="text-sm">Analog Real Value :</label>
        <input type="text" id="analog_real_value" name="analog_real_value" placeholder="Enter Real value" class="p-2 border border-input rounded-md focus:outline-none focus:ring ring-primary" value="<?php echo $digital_real_value;?>" disabled />
      </div>
      
      <div id="digital_sel">
      <h4>3.2 Digital :</h4>
        <div id="digi_min">
          <label for="digital_input"><input type="radio" id="digital_input" name="digital_type" value='input'> Input </label>
          |
          <label for="digital_output"><input type="radio" id="digital_output" name="digital_type" value='input'> Outout </label>
          <br>
          <p>Please select 0 OR 1 :</p>
          <input type="radio" id="digi_in_0" name="digi_input_value" value="0" <?php if($is_digital_zero =='0'){echo "checked";}?>>
          <label for="digi_in_0">0</label><br>
          <input type="radio" id="digi_in_1" name="digi_input_value" value="1" <?php if($is_digital_zero =='1'){echo "checked";}?>>
          <label for="digi_in_1">1</label><br>
          <br>
          <label class="inline-flex items-center cursor-pointer">
                  <span class="text-sm font-medium text-gray-900 dark:text-gray-300">สถานะทำงาน </span>
                  <input type="checkbox" value="<?php echo $is_digital_work;?>"  id="is_digital_work" name="is_digital_work"  class="sr-only peer" <?php if($is_digital_work=='1'){echo "checked";} ?> onclick="active_set_change('is_digital_work')" />
                  &nbsp;&nbsp;
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    &nbsp;&nbsp;<span class="text-sm font-medium text-gray-900 dark:text-gray-300">Off/On</span>
          </label>
        </div>
       
        <!-- <div id="digi_max">
          <label for="digi_output"><input type="checkbox" id="digi_output" name="digi_output"> Output </label>
          <br>
          <p>Please select 0 OR 1 :</p>
          <input type="radio" id="digi_out_0" name="digi_out_value" value="0">
          <label for="digi_out_0">0</label><br>
          <input type="radio" id="digi_out_1" name="digi_out_value" value="1">
          <label for="digi_out_1">1</label><br>
          <br>
          <label for="digi_out_on"><input type="checkbox" id="digi_out_on" name="digi_out_on"> ทำงาน </label>
          <br>
          <label for="digi_out_off"><input type="checkbox" id="digi_out_off" name="digi_out_off"> ไม่ทำงาน </label>
        </div> -->
        <label for="digital_real_value" class="text-sm">Real Value :</label>
        <input type="text" id="digital_real_value" name="digital_real_value" placeholder="Enter Real value" class="p-2 border border-input rounded-md focus:outline-none focus:ring ring-primary" value="<?php echo $digital_real_value;?>" disabled />
      </div>
      <hr>
      <h4>4. Time of work :</h4>
      
      <table style="text-align:center;" id="work_day">
        <tr>
          <td><label for="time_of_work_monday">จันทร์</label></td>
          <td><label for="time_of_work_tuesday">อังคาร</label></td>
          <td><label for="time_of_work_wenday">พุทธ</label></td>
          <td><label for="time_of_work_thursday">พฤหัสบดี</label></td>
          <td><label for="time_of_work_friday">ศุกร์</label></td>
          <td><label for="time_of_work_saturday">เสาร์</label></td>
          <td><label for="time_of_work_sunday">อาทิตย์</label></td>
        </tr>
        <tr>
          <td><input type="checkbox" id="time_of_work_monday" name="time_of_work_day[]" value="1" <?php if (in_array('1', $array_newListTimeOfWork)){echo "checked";}?> ></td>
          <td><input type="checkbox" id="time_of_work_tuesday" name="time_of_work_day[]" value="2" <?php if (in_array('2', $array_newListTimeOfWork)){echo "checked";}?> ></td>
          <td><input type="checkbox" id="time_of_work_wenday" name="time_of_work_day[]" value="3" <?php if (in_array('3', $array_newListTimeOfWork)){echo "checked";}?> ></td>
          <td><input type="checkbox" id="time_of_work_thursday" name="time_of_work_day[]" value="4" <?php if (in_array('4', $array_newListTimeOfWork)){echo "checked";}?> ></td>
          <td><input type="checkbox" id="time_of_work_friday" name="time_of_work_day[]" value="5" <?php if (in_array('5', $array_newListTimeOfWork)){echo "checked";}?> ></td>
          <td><input type="checkbox" id="time_of_work_saturday" name="time_of_work_day[]" value="6" <?php if (in_array('6', $array_newListTimeOfWork)){echo "checked";}?> ></td>
          <td><input type="checkbox" id="time_of_work_sunday" name="time_of_work_day[]" value="0" <?php if (in_array('0', $array_newListTimeOfWork)){echo "checked";}?> ></td>
        </tr>
      </table>
      <hr>
      <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-52 m-auto" id="add_time" onclick="add_time_work();">Add Time for work</button>
      <table style="text-align:center;" id="work_time">
        <tr>
          <th>ทำงาน</th>
          <th>หยุดทำงาน</th>
        </tr>
        <?php
          if(count($rs_time_of_work)>0){
            for($i=0;$i<count($rs_time_of_work);$i++){

            
          ?>
          <tr id="tr_list_<?php echo $i;?>">
              <td>
                <?php echo $rs_time_of_work[$i]->start_work;?>
              </td>
              <td>
                <?php echo $rs_time_of_work[$i]->end_work;?> &nbsp;
                <button
                  class="relative align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[32px] h-10 max-h-[32px] rounded-lg text-xs bg-red-500 text-white shadow-md shadow-red-500/20 hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none"
                  type="button"
                  onclick="confirm_del_workday('<?php echo $i;?>','<?php echo $monitor_id;?>','<?php echo $rs_time_of_work[$i]->monitor_sub_id;?>');"
                >❌</button>
              </td>
        </tr>
          <?php
            }
          }else{        
        ?>
        <tr>
          <td><input class='timepicker' type="time" name="start_time[]" value="00:00" /></td>
          <td><input class='timepicker' type="time" name="end_time[]" value="00:00" /></td>
        </tr>
        <?php
           }
        ?>
      </table>
      
      <table style="margin:0 auto;" id="noti">
        <tr>
          <th><label for="noti_line">Line</label></th>
          <th>
            
            <input type="checkbox" id="noti_line" name="noti_line" <?php if($is_line=='1'){echo "checked";}?>>
            <input type="text" id="noti_line_input" name="noti_line_input" placeholder="Enter Token room id" class="p-2 border border-input rounded-md focus:outline-none focus:ring ring-primary" value="<?php echo $input_line;?>">
          </th>
        </tr>
        <tr>
          <th><label for="noti_email">Email</label></th>
          <th>
            <input type="checkbox" id="noti_email" name="noti_email" <?php if($is_email=='1'){echo "checked";}?>>
            <input type="text" id="noti_email_input" name="noti_email_input" placeholder="Enter email" class="p-2 border border-input rounded-md focus:outline-none focus:ring ring-primary" value="<?php echo $input_email;?>">
          </th>
        </tr>
        <tr>
          <th><label for="noti_sms">SMS</label></th>
          <th>
            <input type="checkbox" id="noti_sms" name="noti_sms" <?php if($is_sms=='1'){echo "checked";}?>>
            <input type="text" id="noti_sms_input" name="noti_sms_input" placeholder="Enter number of sms" class="p-2 border border-input rounded-md focus:outline-none focus:ring ring-primary" value="<?php echo $input_sms;?>">
          </th>
        </tr>
        
        
      </table>

      <button type="submit" class="bg-primary text-primary-foreground p-2 rounded-md hover:bg-primary/80 focus:outline-none focus:ring ring-ring">Submit</button>
    </form>
  </div>
  
  <!-- <script type="text/javascript" src="https://fgelinas.com/code/timepicker/include/jquery-1.9.0.min.js"></script>
    <script type="text/javascript" src="https://fgelinas.com/code/timepicker/include/ui-1.10.0/jquery.ui.core.min.js"></script>
    <script type="text/javascript" src="https://fgelinas.com/code/timepicker/include/ui-1.10.0/jquery.ui.widget.min.js"></script>
    <script type="text/javascript" src="https://fgelinas.com/code/timepicker/include/ui-1.10.0/jquery.ui.position.min.js"></script>
    <script type="text/javascript" src="https://fgelinas.com/code/timepicker/jquery.ui.timepicker.js?v=0.3.3"></script> -->
  <script>
    $( document ).ready(function() {
      var monitor_id = $("#monitor_id").val();
      // timer_get_realtime_data(monitor_id);

      var refresh_data_every_sec = 3; 

      setInterval(function() {
        timer_get_realtime_data(monitor_id);
      }, refresh_data_every_sec*1000 );
      
    });

    function add_time_work(){
      $('#work_time').append('<tr><td><input class="timepicker" type="time" name="start_time[]" value="00:00" /></td><td><input class="timepicker" type="time" name="end_time[]" value="00:00" /></td></tr>');
    }
      
    $("#analog").click(function(){
      $("#digital_sel").hide();
      $("#analog_sel").show();
    });

    $("#digital").click(function(){
      $("#analog_sel").hide();
      $("#digital_sel").show();
    });
    var chk_type_state = '<?php echo $type_state;?>';// A=analog, D=digital
    var chk_type_operation = '<?php echo $type_operation;?>';//IN = input, OUT = output
    if(chk_type_state=="A"){
      $("#analog").click();
    }else{
      $("#digital").click();

      if(chk_type_state=="IN"){
        $("#digital_input").click();
      }else{
        $("#digital_output").click();
      }

    }
    $("#analog").attr("disabled","disabled");
    $("#digital").attr("disabled","disabled");

    $("#digital_input").attr("disabled","disabled");
    $("#digital_output").attr("disabled","disabled");

    $("#analog").attr("class","w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600");
    $("#digital").attr("class","w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600");

    $("#digital_input").attr("class","w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600");
    $("#digital_output").attr("class","w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600");
    
    function active_set_change(elm){ //page1_sql_type = 3
      //Update "monitor" id = elm_index,is_condition = isChecked
      var action_config_isChecked     = $("#"+elm).is(":checked");
      var isChecked                   = 0;
      if(action_config_isChecked){
        isChecked = 1;
      }
      $("#"+elm).val(isChecked);
     

    }
    function confirm_del_workday(elm_index,monitor_id,monitor_sub_id){
      if(confirm("ต้องการลบช่วงเวลา work time นี้?") == true){
        var dataString = 'page2_sql_type=1'+'&monitor_id='+monitor_id+'&monitor_sub_id='+ monitor_sub_id; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_page2_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                $("#tr_list_"+elm_index).hide();
                console.log(jsonData.msg);
              }
              else{
                alert(jsonData.msg+"7");
              }
            } 
          });
         
        
      }else{
        console.log("0");
      }
    }
    function timer_get_realtime_data(monitor_id){
      // console.log(monitor_id);
      var data_realtime = 0;
      var dataString = 'get_realtime_type=1'+'&monitor_id='+monitor_id; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_get_realtime_data_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                var data_realtime = jsonData.data_realtime;
                // console.log(jsonData.data_realtime);
                $("#analog_real_value").val(data_realtime);
                $("#digital_real_value").val(data_realtime);
              }
              else{
                alert(jsonData.msg+"7");
              }
            } 
          });
    }
  </script>
</body>
</html>