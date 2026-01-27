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
    include_once("includes/fn/pg_connect.php");
    $db = pg_connect( "$host $port $dbname $credentials"  );
      if(!$db) {
          echo "Error : Unable to open database\n";
          exit;
      }

?>
<br>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="min-[320px]:w-[98%] xl:w-[98%] m-auto xl:justify-center" style="border:1px solid #f00;">
<?php
      $sql_plus = " ";
      $sortx = "";
      if(!in_array($_SESSION['role_id'], array('88','99'))){
        $sql_plus = " AND a.branch_id='".$_SESSION['branch_id']."' ";
        $sortx = "  a.branch_id asc, ";
      }

      $arrBranch = array();
      $sql = "SELECT branch_id, branch_name	FROM branch_info ORDER BY branch_id asc;";
      $rsBranch   = select($sql,$db);
      if(count($rsBranch)>0){
        for($i=0;$i<count($rsBranch);$i++){
          $arrBranch[$rsBranch[$i]->branch_id] = $rsBranch[$i]->branch_name;
        }
      }
      
      $sql  = "SELECT a.id, a.item_type_id, a.item_name, a.sort, a.status,b.type_path,b.type_name,a.branch_id
                FROM dashboard_item a left join dashboard_type b on a.item_type_id = b.id
                where a.status ='1' ".$sql_plus." order by ".$sortx." a.sort desc,id asc;";
      $rs   = select($sql,$db);
      // echo "<pre>";
      // print_r($rs);
      require_once('includes/php/dashboard_v3/dash_board_logo.php');
      // require_once('includes/php/dashboard_v3/graph_column_1.php');
      if(count($rs)>0){
        for($i=0;$i<count($rs);$i++){
          $item_name    = $rs[$i]->item_name;
          $type_path    = $rs[$i]->type_path;
          $type_name    = $rs[$i]->type_name;
          $dashboard_id = $rs[$i]->id;
          $branch_id    = $rs[$i]->branch_id;
          if(in_array($_SESSION['role_id'], array('88','99'))){
            $branch_name  = " [".$arrBranch[$rs[$i]->branch_id]."]";
          }else{
            $branch_name  = "";
          }
          

          $btn_id       = $dashboard_id;
          require($type_path);
        }
      }
      pg_close($db);
      exit;
  
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

<?php    pg_close($db); ?>

</body>
</html>