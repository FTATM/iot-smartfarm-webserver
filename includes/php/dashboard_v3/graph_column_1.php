
<?php
  /**
   * $dashboard_id
   * 
   */
  // $dashboard_id=7;
  // echo "xxxxxxxxxxxxx";exit;
  // $yesterday = date("Y-m-d", strtotime("-1 month")); 
  // echo "yyy".$yesterday."xxxxxx"; exit;

  // $s_date = date("Y-m-d", strtotime("-3 month"))." 00:00:00";
  // $s_date = date("Y-m-d", strtotime("-1 month"))." 00:00:00";
  $s_date = date("Y-m-d H:i:s", strtotime("-1 day"));
  $e_date   = date('Y-m-d H:i:s');
  // echo "ss".$s_date."|".$e_date; exit;
   $monitor_id = array();
   $data_show = array();
   $label_name = array();
   $label_color_code = array();
   $date_x = array();
   $sql_sub   = "SELECT monitor_id,label_name,label_color_code
	                  FROM dashboard_item_sub_data WHERE dashboard_item_id ='{$dashboard_id}';";
   $rs_sub    = select($sql_sub,$db);
  //  print_r($rs_sub);
   if(count($rs_sub)>0){
      for($j=0;$j<count($rs_sub);$j++){
        $monitor_id[] = $rs_sub[$j]->monitor_id;
        $label_name[$rs_sub[$j]->monitor_id]        = $rs_sub[$j]->label_name;
        $label_color_code[$rs_sub[$j]->monitor_id]  = $rs_sub[$j]->label_color_code;
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

          $sql_get_datax_from_volte_censor = "SELECT 
                                                TO_CHAR(a.create_date, 'yyyy') || '-' || TO_CHAR(a.create_date, 'mm') || '-' || TO_CHAR(a.create_date, 'dd') as date_new,
                                                TO_CHAR(a.create_date, 'hh24') as hour_num,
                                                COALESCE(AVG(a.\"".strtoupper($array_datax[$rs_monitor[$j]->datax_id])."\"),0)::numeric(10,2) as val_num
                                              FROM volte_censor a
                                                LEFT JOIN page_data_manage_device b ON a.name = b.divice_name
                                                LEFT JOIN page_data_manage_group c ON a.location = c.value_map_volte_censor
                                              WHERE b.device_id='{$rs_monitor[$j]->device_id}' AND c.group_id ='{$rs_monitor[$j]->group_id}'
                                                    AND a.create_date between '{$s_date}' and '{$e_date}'
                                                    AND a.\"".strtoupper($array_datax[$rs_monitor[$j]->datax_id])."\" <> '0' 
                                                    AND a.\"".strtoupper($array_datax[$rs_monitor[$j]->datax_id])."\" IS NOT NULL
                                              GROUP BY date_new,hour_num
                                              ORDER BY date_new ASC,hour_num ASC; ";
            $rs_get_datax_from_volte_censor = select($sql_get_datax_from_volte_censor,$db);
            if(count($rs_get_datax_from_volte_censor) > 0){
              $data_show[$rs_monitor[$j]->monitor_id]  = $rs_get_datax_from_volte_censor;
            } 
            $date_diff_start = new DateTime($s_date);
              $date_diff_end	 = new DateTime($e_date);
              $cal = $date_diff_start->diff($date_diff_end);
              $setup_day = $cal->days;
              for($l=0;$l<=$setup_day;$l++){
                // echo "<hr>";
                for($n=0;$n<=23;$n++){
                  $hh24 = $n;
                  if($n<10){$hh24 = '0'.$n;}
                  $date_x[$rs_monitor[$j]->monitor_id][date('Y-m-d', strtotime("+{$l} days",strtotime($s_date)))." ".$hh24.":00"] = 0;
                }
                
              }
      }
  }
  // echo '<br style="clear: both;" />';

      
  // // echo "<hr>";
  // // echo "<pre>";
  // // print_r($date_x);
  // foreach($date_x as $key => $val){
    
  //   foreach($val as $k => $v){
  //     echo $k."<hr>";
  //   }
  //   break;
  // }  
  // exit;
  
?>
<div class="w-11/12 min-[1366px]:w-[53%] 2xl:w-[58.2%] h-[309px] bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 float-left m-3 justify-center">
<h2 class="text-[#555555] mt-[-4px]"><?php echo $item_name;?><?php echo $branch_name;?></h2>

 

  <div id="column-chart_<?php echo $dashboard_id;?>"></div>
    
</div>
<script>


const options3 = {
  // colors: ["#1A56DB", "#FDBA8C"],
  series: [
    // {
    //   name: "Organic",
    //   color: "#1A56DB",
    //   data: [
    //     { x: "Mon", y: 231 },
    //     { x: "Tue", y: 122 },
    //     { x: "Wed", y: 63 },
    //     { x: "Thu", y: 421 },
    //     { x: "Fri", y: 122 },
    //     { x: "Sat", y: 323 },
    //     { x: "Sun", y: 111 },
    //   ],
    // },
    // {
    //   name: "Social media",
    //   color: "#FDBA8C",
    //   data: [
    //     { x: "Mon", y: 232 },
    //     { x: "Tue", y: 113 },
    //     { x: "Wed", y: 341 },
    //     { x: "Thu", y: 224 },
    //     { x: "Fri", y: 522 },
    //     { x: "Sat", y: 411 },
    //     { x: "Sun", y: 243 },
    //   ],
    // },
    <?php
    if(count($monitor_id)>0){
      
      for($j=0;$j<count($monitor_id);$j++){
        $temp_value = $data_show[$monitor_id[$j]];
        $label_name_show = $label_name[$monitor_id[$j]];
        $label_color_code_show = $label_color_code[$monitor_id[$j]];
      
    ?>
    {
      name: "<?php echo $label_name_show;?>",
      color: "<?php echo $label_color_code_show;?>",
      data: [
        // { x: "Mon", y: 231 },
        // { x: "Tue", y: 122 },
        // { x: "Wed", y: 63 },
        // { x: "Thu", y: 421 },
        // { x: "Fri", y: 122 },
        // { x: "Sat", y: 323 },
        // { x: "Sun", y: 111 },
        <?php
            $dataList = array();
            if(count($temp_value)>0){
              for($k=0;$k<count($temp_value);$k++){
                $date_x[$monitor_id[$j]][$temp_value[$k]->date_new." ".$temp_value[$k]->hour_num.":00"] = $temp_value[$k]->val_num;
              } 
            }
              foreach($date_x as $key => $val){
                if($monitor_id[$j]==$key){
                  foreach($val as $k => $v){
                    if($k > date('Y-m-d H:i')){
                      continue;
                    }
                    ?>
                        { x: "<?php echo $k;?>", y: <?php echo $v;?> },
                <?php 
                  }
                }
                continue;
              } 
          ?>
      ],
    },
    <?php
        }
      }
    ?>
  ],
  chart: {
    type: "bar",
    height: "90%",
    width: "100%",
    fontFamily: "Inter, sans-serif",
    toolbar: {
      show: false,
    },
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: "70%",
      borderRadiusApplication: "end",
      borderRadius: 5,
    },
  },
  tooltip: {
    shared: true,
    intersect: false,
    style: {
      fontFamily: "Inter, sans-serif",
    },
  },
  states: {
    hover: {
      filter: {
        type: "darken",
        value: 1,
      },
    },
  },
  stroke: {
    show: true,
    width: 0,
    colors: ["transparent"],
  },
  grid: {
    show: true,
    strokeDashArray: 4,
    padding: {
      left: 2,
      right: 2,
      top: -14
    },
  },
  dataLabels: {
    enabled: false,
  },
  legend: {
    show: true,
  },
  xaxis: {
    floating: false,
    labels: {
      show: false,
      style: {
        fontFamily: "Inter, sans-serif",
        cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
      }
    },
    axisBorder: {
      show: true,
    },
    axisTicks: {
      show: false,
    },
  },
  yaxis: {
    show: true,
  },
  fill: {
    opacity: 1,
  },
}

if(document.getElementById("column-chart_<?php echo $dashboard_id;?>") && typeof ApexCharts !== 'undefined') {
  const chart = new ApexCharts(document.getElementById("column-chart_<?php echo $dashboard_id;?>"), options3);
  chart.render();
}   

</script>