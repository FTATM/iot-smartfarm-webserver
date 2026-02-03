<style>


</style>
<?php 

  /**
   * $dashboard_id
   * 
   */
  // $dashboard_id=7;
  $monitor_id = array();
  $data_show = array();
  $label_name = array();
  $label_color_code = array();
  $sql_sub   = "SELECT monitor_id,label_name,label_color_code
                   FROM dashboard_item_sub_data WHERE dashboard_item_id ='{$dashboard_id}';";
  $rs_sub    = select($sql_sub,$db);
 //  print_r($rs_sub);
  if(count($rs_sub)>0){
     for($j=0;$j<count($rs_sub);$j++){
       $monitor_id[] = $rs_sub[$j]->monitor_id;
       $label_name[$rs_sub[$j]->monitor_id][]        = $rs_sub[$j]->label_name;
       $label_color_code[$rs_sub[$j]->monitor_id][]  = $rs_sub[$j]->label_color_code;
     }
  }
 //  print_r($monitor_id);exit;
 //  echo join(",",$monitor_id);exit;
 $monitor_id_x  = join(",",$monitor_id);
  $sql_monitor ="SELECT monitor_id, group_id, device_id, type_id, is_min, min_value, is_max, max_value, is_input_digi, is_digital_zero, is_line, input_line, is_email, input_email, is_sms, input_sms, createtime, updatetime, status, list_time_of_work,is_condition, config_value_1, config_value_2, config_value_3, config_value_4,is_analog_min_work,is_analog_max_work,datax_id,datax_value,monitor_name,is_digital_work
               FROM page_data_manage_monitor  WHERE status='1' AND monitor_id in({$monitor_id_x}) ORDER BY monitor_id DESC,sort ASC;";
               // echo $sql_monitor;exit;
 $rs_monitor = select($sql_monitor,$db);
 if(count($rs_monitor)>0){
   $sql_sel_datax  = "SELECT datax_id, datax_name, create_time, update_time, sort, status
                           FROM page_data_manage_datax WHERE status='1' ORDER BY datax_id ASC,sort ASC;";
     $rs_sel_datax   = select($sql_sel_datax,$db);
     $array_datax    = array();
     if(count($rs_sel_datax)>0){
       for($j=0;$j<count($rs_sel_datax);$j++){
         $array_datax[$rs_sel_datax[$j]->datax_id] = $rs_sel_datax[$j]->datax_name;
       }
     }
     for($j=0;$j<count($rs_monitor);$j++){
         $sql_get_datax_from_volte_censor = "SELECT a.name as device,a.location as group,a.\"".strtoupper($array_datax[$rs_monitor[$j]->datax_id])."\" as datax
               FROM volte_censor a
               LEFT JOIN page_data_manage_device b ON a.name = b.divice_name
               LEFT JOIN page_data_manage_group c ON a.location = c.value_map_volte_censor
             WHERE b.device_id='{$rs_monitor[$j]->device_id}' AND c.group_id ='{$rs_monitor[$j]->group_id}'
             ORDER BY a.id DESC LIMIT 1;
           ";
           $rs_get_datax_from_volte_censor = select($sql_get_datax_from_volte_censor,$db);
           if(count($rs_get_datax_from_volte_censor)=='1' && $rs_get_datax_from_volte_censor[0]->datax != ""){
             $data_show[$rs_monitor[$j]->monitor_id][]  = $rs_get_datax_from_volte_censor[0]->datax;
           }else{
             $data_show[$rs_monitor[$j]->monitor_id][]  = 0;
           } 
     }
 }

 if(count($monitor_id)>0){
   for($j=0;$j<count($monitor_id);$j++){
     $temp_value = $data_show[$monitor_id[$j]][0];
     $label_name_show = $label_name[$monitor_id[$j]][0];
   }
 }

 $img_sw_state = $temp_value;

  $isChecked = "";
  if($img_sw_state == '1'){
    $img_src = "includes/images/005.png";
    $status_display = "On";
    $isChecked = "checked";
  }else{
    $img_src = "includes/images/006.png";
    $status_display = "Off";
  }
  
?>
<div class="w-11/12 
            min-[320px]:w-[25.9%] min-[320px]:h-[100px]
            xl:w-44 2xl:w-[11.5%] 
            xl:min-h-[309px] 
            bg-white rounded-lg 
            text-[#333333] shadow dark:bg-gray-800 m-3 float-left justify-center">
      <div class="justify-center text-center min-[320px]:text-[10px] text-[#777777] xl:text-[20px]">
        <!-- <div><p>S.W.</?php echo $btn_id;?></p></div>
        <div><b>(S.W.</?php echo $btn_id;?>)</b></div> -->
        <div><p><?php echo $item_name;?><?php echo $branch_name;?></p></div>

        <!-- <img src="</?php echo $img_src;?>" class="min-[320px]:w-[65px] xl:w-[100px] m-auto min-[320px]:mt-[5px]" alt="Switch"> -->
        <!-- <label class="inline-flex items-center cursor-pointer">
          <input type="checkbox" value=""  id="enable_config_</?php echo $monitor_id;?>" class="sr-only peer" </?php echo $isChecked;?>  />
          <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 m-auto justify-center text-center xl:mt-8 min-[320px]:mt-3" ></div>
        </lable> -->
        <label class=" inline-flex relative flex items-center mb-5 cursor-pointer m-auto justify-center text-center min-[320px]:mt-2 xl:mt-[75px]">
          <input type="checkbox" value="" id="sent_sw_<?php echo $dashboard_id;?>" class="sr-only peer" onclick="sent_sw_change_<?php echo $dashboard_id;?>('<?php echo $dashboard_id;?>')" <?php echo $isChecked;?>>
          <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600 hover:peer-checked:bg-indigo-700"></div>
        </label>
      </div>
    
    <div class="flex justify-center min-[320px]:mt-[-11px] xl:mt-[15px] text-[#555555] xl:mt-[25px] text-center">
      <div class="min-[320px]:text-[10px] xl:text-[20px]"><p id="status_sent_sw_<?php echo $dashboard_id;?>"><?php echo $status_display;?></p></div>
    </div>
</div>
<?php
 /**
  * clear data
  */
  $label_name_show = "";
  $temp_value = 0;

?>

<!--https://uiverse.io/vinodjangid07/quick-moth-22-->

<script>
  function sent_sw_change_<?php echo $dashboard_id;?>(elm_index){ //page1_sql_type = 2
      //Update "monitor" id = elm_index,config_value_2 = isChecked 
      //|insert "volte_censor" location = group, name = device, DATA02
      var sent_sw_isChecked     = $("#sent_sw_"+elm_index).is(":checked");
        // if(sent_sw_isChecked){
        //   $("#sent_sw_"+elm_index).prop('checked', false);
        // }else{
        //   $("#sent_sw_"+elm_index).prop('checked', true);
        // }
      

      var isChecked = 0;
      if(sent_sw_isChecked){
        isChecked = 1;
      }
      if(isChecked=='1'){
        $("#status_sent_sw_<?php echo $dashboard_id;?>").html('On');
      }else{
        $("#status_sent_sw_<?php echo $dashboard_id;?>").html('Off');
      }
      // console.log(sent_sw_isChecked);
      var dataString = 'page1_sql_type=2'+'&dashboard_id='+elm_index+'&config_value_2='+ isChecked; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_dashboard_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                console.log(jsonData.msg);
              }
              else{
                alert(jsonData.msg+"2");
              }
            } 
          });

    }
</script>
