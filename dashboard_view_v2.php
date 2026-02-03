<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="includes/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-background text-foreground bg-zinc-100">
<?php
    include_once("nav_bar.php");
    /**
     * https://flowbite.com/docs/plugins/charts/#getting-started
     */

?>
<br>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="min-[320px]:w-[98%] xl:w-[98%] m-auto xl:justify-center" style="border:0px solid #f00;">
<?php
// $data1 = 'includes/php/dashboard_v2/graph_liner_1.php';
// require_once($data1);
  // require_once('includes/php/dashboard_v2/graph_liner_1.php');
  // require_once('includes/php/dashboard_v2/graph_liner_2.php');
  // require_once('includes/php/dashboard_v2/graph_column_1.php');
  // require_once('includes/php/dashboard_v2/graph_bar_chart_1.php');
  
  require_once('includes/php/dashboard_v2/dash_board_logo.php');
  $temp_value = 30.53;
  require('includes/php/dashboard_v2/graph_semi_circle_pie_chart_1.php');
  $temp_value = 65.80;
  require('includes/php/dashboard_v2/graph_semi_circle_pie_chart_1.php');
  require_once('includes/php/dashboard_v2/graph_pie_chart_info_1.php');
  $temp_value = 1.29;
  require('includes/php/dashboard_v2/graph_semi_circle_pie_chart_1.php');
  require_once('includes/php/dashboard_v2/graph_termometer.php');
  require_once('includes/php/dashboard_v2/graph_digital_clock_and_date.php');
  
  // require_once('includes/php/dashboard_v2/graph_donut_chart_info_1.php');
  // require_once('includes/php/dashboard_v2/graph_radial_chart_info_1.php');
  // require_once('includes/php/dashboard_v2/graph_radial_chart_info_2.php');
  require_once('includes/php/dashboard_v2/graph_liner_label_chart_3.php');
  // require_once('includes/php/dashboard_v2/graph_liner_tooltip_chart_1.php');
  // require_once('includes/php/dashboard_v2/graph_liner_1.php');
  require_once('includes/php/dashboard_v2/graph_scatter.php');
  
  // require_once('includes/php/dashboard_v2/graph_semi_circle_pie_chart.php');
  $btn_id = 1;
  $status_bool = "0";
  require('includes/php/dashboard_v2/graph_switch_btn.php');
  $btn_id = 2;
  $status_bool = "0";
  require('includes/php/dashboard_v2/graph_switch_btn.php');
  $btn_id = 3;
  $status_bool = "1";
  require('includes/php/dashboard_v2/graph_switch_btn.php');
  $btn_id = 4;
  $status_bool = "0";
  require('includes/php/dashboard_v2/graph_switch_btn.php');
  $btn_id = 5;
  $status_bool = "1";
  require('includes/php/dashboard_v2/graph_switch_btn.php');
  $btn_id = 6;
  $status_bool = "0";
  require('includes/php/dashboard_v2/graph_switch_btn.php');
  require('includes/php/dashboard_v2/graph_slide_bar.php');

  $btn_id = 1;
  $lamp_state = '1';
  require('includes/php/dashboard_v2/graph_lamp_img.php');
  $btn_id = 2;
  $lamp_state = '0';
  require('includes/php/dashboard_v2/graph_lamp_img.php');
  $btn_id = 3;
  $lamp_state = '1';
  require('includes/php/dashboard_v2/graph_lamp_img.php');

  $btn_id = 1;
  $img_sw_state = '1';
  require('includes/php/dashboard_v2/graph_switch_img.php');
  $btn_id = 2;
  $img_sw_state = '0';
  require('includes/php/dashboard_v2/graph_switch_img.php');
  $btn_id = 3;
  $img_sw_state = '1';
  require('includes/php/dashboard_v2/graph_switch_img.php');

  $btn_id = 1;
  $img_blink_state = '1';
  require('includes/php/dashboard_v2/graph_wroning_blinkblink_img.php');
  $btn_id = 2;
  $img_blink_state = '0';
  require('includes/php/dashboard_v2/graph_wroning_blinkblink_img.php');
  $btn_id = 3;
  $img_blink_state = '1';
  require('includes/php/dashboard_v2/graph_wroning_blinkblink_img.php');

  
  
  
  
  
?>
<br style="clear: both;" />
</div>

  <!-- <div class="grid grid-flow-col justify-stretch gap-2 font-mono text-white text-sm font-bold leading-6 bg-stripes-fuchsia rounded-lg">
    <div class="w-full h-14 rounded-lg flex items-center justify-center bg-fuchsia-500 shadow-lg">01</div>
    <div class="h-14 rounded-lg flex items-center justify-center bg-fuchsia-500 shadow-lg">02</div>
    <div class="h-14 rounded-lg flex items-center justify-center bg-fuchsia-500 shadow-lg">03</div>
    <div class="h-14 rounded-lg flex items-center justify-center bg-fuchsia-500 shadow-lg">01</div>
    <div class="h-14 rounded-lg flex items-center justify-center bg-fuchsia-500 shadow-lg">02</div>
    <div class="h-14 rounded-lg flex items-center justify-center bg-fuchsia-500 shadow-lg">03</div>
  </div> -->

<?php
  // require_once('includes/php/dashboard_v2/graph_geo_chart_1.php');
?>
<!-- <br style="clear: both;" /> -->



</body>
</html>