<style>
.img_gray_scale {
  filter: grayscale(100%);
}

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

 $img_blink_state = $temp_value;
 $img_gray_scale = "";
  if($img_blink_state == '1'){
    $img_src = "includes/images/blink05.gif";
    $status_display = "On";
  }else{
    $img_src = "includes/images/blink_0.gif";
    $status_display = "Off";
    $img_gray_scale = " img_gray_scale ";
  }
  
?>
<div class="w-11/12 
            min-[320px]:w-[25.9%] min-[320px]:h-[100px]
            xl:w-44 2xl:w-[11.5%] 
            xl:min-h-[309px] 
            bg-white rounded-lg 
            text-[#333333] shadow dark:bg-gray-800 m-3 float-left justify-center">
      <div class="justify-center text-center min-[320px]:text-[10px] text-[#777777] xl:text-[20px]">
        <!-- <div><p>Blink<?php echo $btn_id;?></p></div>
        <div><b>(Blink<?php echo $btn_id;?>)</b></div> -->
        <div><p><?php echo $item_name;?><?php echo $branch_name;?></p></div>

      </div>
    <img src="<?php echo $img_src;?>" class="min-[320px]:w-[40px] xl:w-[100px] xl:h-[100px] m-auto xl:mt-[40px] <?php echo $img_gray_scale;?>" alt="Blinking">
    <div class="flex justify-center min-[320px]:mt-[1px] mt-[1px] text-[#555555] xl:mt-[10px]">
      <div class="min-[320px]:mt-[2px] min-[320px]:text-[10px] xl:text-[20px]"><p><?php echo $status_display;?></p></div>
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
  
</script>
