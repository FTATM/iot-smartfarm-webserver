<style>
  #clockdate{
    margin-top:45px;
  }
 .clockdate-wrapper {
    background-color: #333;
    padding:25px;
    max-width:350px;
    width:100%;
    text-align:center;
    border-radius:5px;
    margin:0 auto;
  
}
#clock{
    background-color:#333;
    font-family: sans-serif;
    font-size:60px;
    text-shadow:0px 0px 1px #fff;
    color:#fff;
}
#clock span {
    color:#888;
    text-shadow:0px 0px 1px #333;
    font-size:50px;
    position:relative;
    top:-5px;
    left:10px;
}
#date {
    letter-spacing:3px;
    font-size:14px;
    font-family:arial,sans-serif;
    color:#fff;
}
</style>

<div class="w-11/12 min-h-[309px] xl:w-1/5 bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 m-3 float-left justify-center">
<h2 class="text-[#555555]">CLOCK</h2>
  <div id="clockdate">
    <div class="clockdate-wrapper">
      <div id="clock"></div>
      <div id="date"></div>
    </div>
  </div>

</div>


<!--https://codepen.io/paarmita/pen/YroXwv-->

<script>
  startTime();
function startTime() {
    var today = new Date();
    var hr = today.getHours();
    var min = today.getMinutes();
    var sec = today.getSeconds();
    /*ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";*/
  ap = "";
    hr = (hr == 0) ? 12 : hr;
    /*hr = (hr > 12) ? hr - 12 : hr;*/
    //Add a zero in front of numbers<10
    hr = checkTime(hr);
    min = checkTime(min);
    sec = checkTime(sec);
    document.getElementById("clock").innerHTML = hr + ":" + min + ":" + sec + " " + ap;
    
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    var curWeekDay = days[today.getDay()];
    var curDay = today.getDate();
    var curMonth = months[today.getMonth()];
    var curYear = today.getFullYear();
    var date = curWeekDay+", "+curDay+" "+curMonth+" "+curYear;
    document.getElementById("date").innerHTML = date;
    
    var time = setTimeout(function(){ startTime() }, 500);
}
function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}
</script>
