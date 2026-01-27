<style>


</style>
<?php $monitor_id=1;?>
<div class="w-11/12 
            min-[320px]:max-h-[100px]
            xl:w-[20%]
            xl:min-h-[309px] 
            bg-white rounded-lg 
            text-[#333333] shadow dark:bg-gray-800 p-4 md:p-6 m-3 float-left justify-center">

    <label for="input_slide_<?php echo $monitor_id;?>">
      <div id="display_slide_<?php echo $monitor_id;?>" class="w-2/4 min-[320px]:text-sm min-[320px]:font-medium m-auto flex items-center justify-center border-slate-800 text-5xl xl:mt-[70px]">1,234</div>
    </label>
    
    <input type="range" id="input_slide_<?php echo $monitor_id;?>" class="w-2/4 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700" name="input_slide_<?php echo $monitor_id;?>" min="0" max="100" value="1234" oninput="update_slidebar_val('<?php echo $monitor_id;?>');" onchange="change_slidebar_val('<?php echo $monitor_id;?>');" >
    <div class="xl:text-sm min-[320px]:text-[10px] font-medium text-gray-900 dark:text-gray-300 justify-center text-center"> Range 0 to 100 </div>
</div>


<!--https://uiverse.io/vinodjangid07/quick-moth-22-->

<script>
  function update_slidebar_val(elm_index){
        var slide_val     = $("#input_slide_"+elm_index).val();
        // console.log(slide_val);
        var slide_display = $("#display_slide_"+elm_index).html(numberWithCommas(slide_val));
  }
  function change_slidebar_val(elm_index){ 

  }
  function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
</script>
