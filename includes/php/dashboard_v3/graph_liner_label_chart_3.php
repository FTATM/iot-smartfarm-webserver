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
  $s_date = date("Y-m-d", strtotime("-1 day"))." 00:00:00";
  $e_date   = date('Y-m-d')." 23:59:59";
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
          // $sql_get_datax_from_volte_censor = "SELECT a.name as device,a.location as group,a.\"".strtoupper($array_datax[$rs_monitor[$j]->datax_id])."\" as datax
          //       FROM volte_censor a
          //       LEFT JOIN page_data_manage_device b ON a.name = b.divice_name
          //       LEFT JOIN page_data_manage_group c ON a.location = c.value_map_volte_censor
          //     WHERE b.device_id='{$rs_monitor[$j]->device_id}' AND c.group_id ='{$rs_monitor[$j]->group_id}'
          //     ORDER BY a.id DESC LIMIT 1;
          //   ";
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


<div class="w-11/12 min-[1366px]:w-[53%] 2xl:w-[54%] h-[309px] bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 float-left m-3 justify-center">
<h2 class="text-[#555555]"><?php echo $item_name;?><?php echo $branch_name;?></h2>
  <!-- <div class="flex justify-between mb-5">
    <div>
      <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">$12,423</h5>
      <p class="text-base font-normal text-gray-500 dark:text-gray-400">Sales this week</p>
    </div>
    <div
      class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
      23%
      <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
      </svg>
    </div>
  </div> -->
  <div id="data-labels-chart_<?php echo $dashboard_id;?>"></div>
  <!-- <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between mt-5">
    <div class="flex justify-between items-center pt-5"> -->
      <!-- Button -->
      <!-- <button
        id="dropdownDefaultButton"
        data-dropdown-toggle="lastDaysdropdown"
        data-dropdown-placement="bottom"
        class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
        type="button">
        Last 7 days
        <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
      </button> -->
      <!-- Dropdown menu -->
      <!-- <div id="lastDaysdropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
          <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 7 days</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 30 days</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 90 days</a>
            </li>
          </ul>
      </div>
      <a
        href="#"
        class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
        Sales Report
        <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
      </a>
    </div>
  </div> -->
</div>
<script>
  
const options5_<?php echo $dashboard_id;?> = {
// enable and customize data labels using the following example, learn more from here: https://apexcharts.com/docs/datalabels/
dataLabels: {
  enabled: true,
  // offsetX: 10,
  style: {
    cssClass: 'text-xs text-white font-medium'
  },
},
grid: {
  show: true,
  strokeDashArray: 4,
  padding: {
    left: 16,
    right: 16,
    top: -26
  },
},
series: [
  // {
  //   name: "Developer Edition",
  //   data: [150, 141, 145, 152, 135, 125],
  //   color: "#1A56DB",
  // },
  // {
  //   name: "Designer Edition",
  //   data: [64, 41, 76, 41, 113, 173],
  //   color: "#7E3BF2",
  // },
  // {
  //   name: "Data Edition",
  //   data: [15, 31, 86, 71, 20, 73],
  //   color: "#EA3DF2",
  // },
  // {
  //   name: "Data line 2",
  //   data: [41, 96, 76, 15, 29, 37],
  //   color: "#036683",
  // },
  // {
  //   name: "Data line 3",
  //   data: [81, 11, 66, 29, 46, 26],
  //   color: "#2f9b62",
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
        data: [
          <?php
            $dataList = array();
            if(count($temp_value)>0){
              for($k=0;$k<count($temp_value);$k++){
                $date_x[$monitor_id[$j]][$temp_value[$k]->date_new." ".$temp_value[$k]->hour_num.":00"] = $temp_value[$k]->val_num;
              } 
            }
            echo join(",",$date_x[$monitor_id[$j]]); 
          ?>
        ],
        color: "<?php echo $label_color_code_show;?>",
      },
  <?php
      }
    }
  ?>
],
chart: {
  height: "90%",
  maxWidth: "100%",
  type: "area",
  fontFamily: "Inter, sans-serif",
  dropShadow: {
    enabled: false,
  },
  toolbar: {
    show: false,
  },
},
tooltip: {
  enabled: true,
  x: {
    show: false,
  },
},
legend: {
  show: true
},
fill: {
  type: "gradient",
  gradient: {
    opacityFrom: 0.55,
    opacityTo: 0,
    shade: "#1C64F2",
    gradientToColors: ["#1C64F2"],
  },
},
stroke: {
  width: 6,
},
xaxis: {
  // categories: ['01 February', '02 February', '03 February', '04 February', '05 February', '06 February', '07 February'],
  categories: [
    <?php
    $new_label = array();
      foreach($date_x as $key => $val){
        foreach($val as $k => $v){
          // echo $k;
          $new_label[] = "'".$k."'";
        }
        break;
      }  
      echo join(",",$new_label);
    ?>

  ],
  labels: {
    show: false,
  },
  axisBorder: {
    show: false,
  },
  axisTicks: {
    show: false,
  },
},
yaxis: {
  show: true,
  labels: {
    formatter: function (value) {
      return value;
    }
  }
},
}

if (document.getElementById("data-labels-chart_<?php echo $dashboard_id;?>") && typeof ApexCharts !== 'undefined') {
const chart = new ApexCharts(document.getElementById("data-labels-chart_<?php echo $dashboard_id;?>"), options5_<?php echo $dashboard_id;?>);
chart.render();
}
</script>