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
  $s_date = date("Y-m-d", strtotime("-30 day"))." 00:00:00";
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
          $sql_get_datax_from_volte_censor = "SELECT 
                                                TO_CHAR(a.create_date, 'yyyy') || '-' || TO_CHAR(a.create_date, 'mm') || '-' || TO_CHAR(a.create_date, 'dd') as date_new,
                                                TO_CHAR(a.create_date, 'hh24') as hour_num,
                                                COALESCE(MAX(a.\"".strtoupper($array_datax[$rs_monitor[$j]->datax_id])."\"),0)::numeric(10,2) as val_num_max,
                                                COALESCE(MIN(a.\"".strtoupper($array_datax[$rs_monitor[$j]->datax_id])."\"),0)::numeric(10,2) as val_num_min
                                              FROM volte_censor a
                                                LEFT JOIN page_data_manage_device b ON a.name = b.divice_name
                                                LEFT JOIN page_data_manage_group c ON a.location = c.value_map_volte_censor
                                              WHERE b.device_id='{$rs_monitor[$j]->device_id}' AND c.group_id ='{$rs_monitor[$j]->group_id}'
                                                    AND a.create_date between '{$s_date}' and '{$e_date}'
                                                    AND a.\"".strtoupper($array_datax[$rs_monitor[$j]->datax_id])."\" IS NOT NULL
                                              GROUP BY date_new,hour_num
                                              ORDER BY date_new ASC,hour_num ASC; ";
                                              // echo "<br>xxxxxxxxxxxxxxxx";
                                              // echo $sql_get_datax_from_volte_censor;exit;
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

<div class="w-11/12 xl:w-1/5 max-h-[309px] bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 m-3 float-left justify-center">
<h2 class="text-[#555555]"><?php echo $item_name;?><?php echo $branch_name;?></h2>
  <div id="chart_scatter_<?php echo $dashboard_id;?>" class="p-4"></div>
   

</div>


<!--https://apexcharts.com/javascript-chart-demos/scatter-charts/basic/#-->

<script>
   var options_<?php echo $dashboard_id;?> = {
          series: [
            // {
            //   name: "SAMPLE A",
            //   data: [
            //   [16.4, 5.4], [21.7, 2], [25.4, 3], [19, 2], [10.9, 1], [13.6, 3.2], [10.9, 7.4], [10.9, 0], [10.9, 8.2], [16.4, 0], [16.4, 1.8], [13.6, 0.3], [13.6, 0], [29.9, 0], [27.1, 2.3], [16.4, 0], [13.6, 3.7], [10.9, 5.2], [16.4, 6.5], [10.9, 0], [24.5, 7.1], [10.9, 0], [8.1, 4.7], [19, 0], [21.7, 1.8], [27.1, 0], [24.5, 0], [27.1, 0], [29.9, 1.5], [27.1, 0.8], [22.1, 2]]
            //   },{
            //   name: "SAMPLE B",
            //   data: [
            //   [36.4, 13.4], [1.7, 11], [5.4, 8], [9, 17], [1.9, 4], [3.6, 12.2], [1.9, 14.4], [1.9, 9], [1.9, 13.2], [1.4, 7], [6.4, 8.8], [3.6, 4.3], [1.6, 10], [9.9, 2], [7.1, 15], [1.4, 0], [3.6, 13.7], [1.9, 15.2], [6.4, 16.5], [0.9, 10], [4.5, 17.1], [10.9, 10], [0.1, 14.7], [9, 10], [12.7, 11.8], [2.1, 10], [2.5, 10], [27.1, 10], [2.9, 11.5], [7.1, 10.8], [2.1, 12]]
            // },{
            //   name: "SAMPLE C",
            //   data: [
            //   [21.7, 3], [23.6, 3.5], [24.6, 3], [29.9, 3], [21.7, 20], [23, 2], [10.9, 3], [28, 4], [27.1, 0.3], [16.4, 4], [13.6, 0], [19, 5], [22.4, 3], [24.5, 3], [32.6, 3], [27.1, 4], [29.6, 6], [31.6, 8], [21.6, 5], [20.9, 4], [22.4, 0], [32.6, 10.3], [29.7, 20.8], [24.5, 0.8], [21.4, 0], [21.7, 6.9], [28.6, 7.7], [15.4, 0], [18.1, 0], [33.4, 0], [16.4, 0]]
            // },
            <?php
              if(count($monitor_id)>0){
                // echo "<pre>";
                for($j=0;$j<count($monitor_id);$j++){
                  $temp_value = $data_show[$monitor_id[$j]];
                  $label_name_show = $label_name[$monitor_id[$j]];
                  $label_color_code_show = $label_color_code[$monitor_id[$j]];
                  
                  // print_r($temp_value);
                  // echo "<hr>";
                
            ?>
            {
              name: "<?php echo $label_name_show;?>",
              data: [
                <?php
                  for($k=0;$k<count($temp_value);$k++){
                  ?>
                  [<?php echo $temp_value[$k]->val_num_max.",".$temp_value[$k]->val_num_min;?>],
            
                  <?php
                  }
                  ?>
            ]
              },
              <?php
                  }
                }
              ?>
          ],
          chart: {
          height: "100%",
          maxWidth: "100%",
          type: 'scatter',
          zoom: {
            enabled: false,
            type: 'xy'
          },dropShadow: {
            enabled: false,
          },
          toolbar: {
            show: false,
          },
        },
        xaxis: {
          tickAmount: 10,
          labels: {
            formatter: function(val) {
              return parseFloat(val).toFixed(1)
            }
          }
        },
        yaxis: {
          tickAmount: 7
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart_scatter_<?php echo $dashboard_id;?>"), options_<?php echo $dashboard_id;?>);
        chart.render();
</script>
