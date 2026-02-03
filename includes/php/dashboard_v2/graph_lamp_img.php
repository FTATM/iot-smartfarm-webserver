<style>


</style>
<?php 
  if($lamp_state == '1'){
    $img_src = "includes/images/Lamp-2-1.gif";
    $status_display = "On";
  }else{
    $img_src = "includes/images/Lamp-4-1.gif";
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
        <div><p>Lamp<?php echo $btn_id;?></p></div>
        <div><b>(Lamp<?php echo $btn_id;?>)</b></div>
        
      </div>
      <img src="<?php echo $img_src;?>" class="min-[320px]:w-[40px] xl:w-[100px] xl:h-[100px] m-auto xl:mt-[40px]"  alt="Lamp">
    <div class="flex justify-center min-[320px]:mt-[-1px] mt-[1px] text-[#555555] xl:mt-[10px]">
      <div class="min-[320px]:mt-[2px] min-[320px]:text-[10px] xl:text-[20px]"><p><?php echo $status_display;?></p></div>
    </div>
</div>


<!--https://uiverse.io/vinodjangid07/quick-moth-22-->

<script>
  
</script>
