<style>


</style>
<?php 
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
        <div><p>S.W.<?php echo $btn_id;?></p></div>
        <div><b>(S.W.<?php echo $btn_id;?>)</b></div>
        <!-- <img src="<?php echo $img_src;?>" class="min-[320px]:w-[65px] xl:w-[100px] m-auto min-[320px]:mt-[5px]" alt="Switch"> -->
        <!-- <label class="inline-flex items-center cursor-pointer">
          <input type="checkbox" value=""  id="enable_config_<?php echo $monitor_id;?>" class="sr-only peer" <?php echo $isChecked;?>  />
          <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 m-auto justify-center text-center xl:mt-8 min-[320px]:mt-3" ></div>
        </lable> -->
        <label class=" inline-flex relative flex items-center mb-5 cursor-pointer m-auto justify-center text-center min-[320px]:mt-2 xl:mt-[75px]">
          <input type="checkbox" value="" id="enable_config_<?php echo $monitor_id;?>" class="sr-only peer" <?php echo $isChecked;?>>
          <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600 hover:peer-checked:bg-indigo-700"></div>
        </label>
      </div>
    
    <div class="flex justify-center min-[320px]:mt-[-11px] xl:mt-[15px] text-[#555555] xl:mt-[25px] text-center">
      <div class="min-[320px]:text-[10px] xl:text-[20px]"><p><?php echo $status_display;?></p></div>
    </div>
</div>


<!--https://uiverse.io/vinodjangid07/quick-moth-22-->

<script>
  
</script>
