<style>
 /* From Uiverse.io by vinodjangid07 */ 
#checkbox_btn<?php echo $btn_id;?> {
  /* display: none; */
}

.switch_btn1 {
  position: relative;
  width: 70px;
  height: 70px;
  background-color: rgb(99, 99, 99);
  border-radius: 50%;
  z-index: 1;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid rgb(126, 126, 126);
  box-shadow: 0px 0px 3px rgb(2, 2, 2) inset;
}
.switch_btn1 svg {
  width: 1.2em;
}
.switch_btn1 svg path {
  fill: rgb(48, 48, 48);
}
#checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 {
  box-shadow: 0px 0px 1px rgb(151, 243, 255) inset,
    0px 0px 2px rgb(151, 243, 255) inset, 0px 0px 10px rgb(151, 243, 255) inset,
    0px 0px 40px rgb(151, 243, 255), 0px 0px 100px rgb(151, 243, 255),
    0px 0px 5px rgb(151, 243, 255);
  border: 2px solid rgb(255, 255, 255);
  background-color: rgb(146, 180, 184);
}
#checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 svg {
  filter: drop-shadow(0px 0px 5px rgb(151, 243, 255));
}
#checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 svg path {
  fill: rgb(255, 255, 255);
}
@media screen and (max-width: 1024px) {
  .switch_btn1 {
      position: relative;
      width: 35px;
      height: 35px;
      background-color: rgb(99, 99, 99);
      border-radius: 50%;
      z-index: 1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid rgb(126, 126, 126);
      box-shadow: 0px 0px 3px rgb(2, 2, 2) inset;
    }
}
@media screen and (max-width: 768px) {
  .switch_btn1 {
      position: relative;
      width: 35px;
      height: 35px;
      background-color: rgb(99, 99, 99);
      border-radius: 50%;
      z-index: 1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid rgb(126, 126, 126);
      box-shadow: 0px 0px 3px rgb(2, 2, 2) inset;
    }
    .switch_btn1 svg {
      width: 1.2em;
    }
    .switch_btn1 svg path {
      fill: rgb(48, 48, 48);
    }
    #checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 {
      box-shadow: 0px 0px 1px rgb(151, 243, 255) inset,
        0px 0px 2px rgb(151, 243, 255) inset, 0px 0px 10px rgb(151, 243, 255) inset,
        0px 0px 40px rgb(151, 243, 255), 0px 0px 100px rgb(151, 243, 255),
        0px 0px 5px rgb(151, 243, 255);
      border: 2px solid rgb(255, 255, 255);
      background-color: rgb(146, 180, 184);
    }
    #checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 svg {
      filter: drop-shadow(0px 0px 5px rgb(151, 243, 255));
    }
    #checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 svg path {
      fill: rgb(255, 255, 255);
    }
}
</style>
<?php
  
  if($status_bool == '1'){
    $status_display = "On";
    $checked_state = "checked";
  }else{
    $status_display = "Off";
    $checked_state = "";
  }
?>
<div class="w-11/12 
            min-[320px]:w-[25.9%] min-[320px]:h-[100px]
            xl:w-44 2xl:w-[11.5%] 
            xl:min-h-[309px] 
            bg-white rounded-lg shadow dark:bg-gray-800 m-3 float-left justify-center">
    <div class="justify-center text-center min-[320px]:text-[10px] text-[#777777] xl:text-[20px]">
      <div><p>efan<?php echo $btn_id;?></p></div>
      <div><b>(sensor<?php echo $btn_id;?>)</b></div>
    </div>
    <input id="checkbox_btn<?php echo $btn_id;?>" type="checkbox" style="display:none;" <?php echo $checked_state;?>  />
    <label class="m-auto xl:mt-[80px] min-[320px]:mt-[5px] switch_btn1 " for="checkbox_btn<?php echo $btn_id;?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="slider">
        <path
          d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V256c0 17.7 14.3 32 32 32s32-14.3 32-32V32zM143.5 120.6c13.6-11.3 15.4-31.5 4.1-45.1s-31.5-15.4-45.1-4.1C49.7 115.4 16 181.8 16 256c0 132.5 107.5 240 240 240s240-107.5 240-240c0-74.2-33.8-140.6-86.6-184.6c-13.6-11.3-33.8-9.4-45.1 4.1s-9.4 33.8 4.1 45.1c38.9 32.3 63.5 81 63.5 135.4c0 97.2-78.8 176-176 176s-176-78.8-176-176c0-54.4 24.7-103.1 63.5-135.4z"
        ></path>
      </svg>
    </label>
    <div class="flex justify-center min-[320px]:mt-[1px] mt-[1px] text-[#555555] xl:mt-[20px]">
      <div class="min-[320px]:mt-[2px] min-[320px]:text-[10px] xl:text-[20px]"><p><?php echo $status_display;?></p></div>
    </div>
</div>


<!--https://uiverse.io/vinodjangid07/quick-moth-22-->

<script>

</script>
