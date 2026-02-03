// ฟังก์ชันสำหรับแสดงวันที่ปัจจุบัน
(function() {
  // แปลงเดือนเป็นภาษาไทย
  const thaiMonths = [
    'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน',
    'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
    'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
  ];

  function updateDate() {
    const today = new Date();
    const day = today.getDate();
    const month = thaiMonths[today.getMonth()];
    const year = today.getFullYear() + 543; // แปลงเป็นปี พ.ศ.

    // จัดรูปแบบวันที่
    const formattedDate = `${day} ${month} ${year}`;

    // แสดงผลในองค์ประกอบที่มี id="start-date"
    const dateElement = document.getElementById('start-date');
    if (dateElement) {
      dateElement.textContent = formattedDate;
      console.log('วันที่ถูกอัพเดทแล้ว:', formattedDate);
    } else {
      console.error('ไม่พบ element ที่มี id="start-date"');
    }
  }

  // ลองอัพเดททันทีก่อน
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateDate);
  } else {
    updateDate();
  }
})();