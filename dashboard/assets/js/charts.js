/**
 * Charts Controller
 * จัดการการแสดงผลกราฟด้วย Chart.js
 */

let doChart = null;
let currentPeriod = 'day'; // 'day' หรือ 'month'

// ฟังก์ชันอัปเดตกราฟ DO
function updateDOChart(dataArray) {
    if (!dataArray || dataArray.length === 0) {
        console.log('No DO data available');
        return;
    }
    
    // เรียงข้อมูลตามเวลา
    const sortedData = [...dataArray].sort((a, b) => {
        return new Date(a.created_at) - new Date(b.created_at);
    });
    
    // กรองข้อมูลตามช่วงเวลา
    const filteredData = filterDataByPeriod(sortedData, currentPeriod);
    
    // เตรียม labels และ data
    const labels = filteredData.map(item => {
        const date = new Date(item.created_at);
        if (currentPeriod === 'day') {
            // แสดงเฉพาะเวลา
            return date.toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
        } else {
            // แสดงวันที่
            return date.toLocaleDateString('th-TH', { day: 'numeric', month: 'short' });
        }
    });
    
    const values = filteredData.map(item => parseFloat(item.value));
    
    // ลบกราฟเก่า
    const chartContainer = document.getElementById('do-chart');
    chartContainer.innerHTML = '<canvas id="doChartCanvas"></canvas>';
    
    // สร้างกราฟใหม่
    const ctx = document.getElementById('doChartCanvas').getContext('2d');
    
    doChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'DO (mg/L)',
                data: values,
                borderColor: '#ff8021',
                backgroundColor: 'rgba(255, 128, 33, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: '#ff8021',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(29, 19, 12, 0.9)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#ff8021',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return `DO: ${context.parsed.y.toFixed(2)} mg/L`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10,
                            family: "'Inter', 'Noto Sans Thai', sans-serif"
                        },
                        color: '#78716c',
                        maxRotation: 0,
                        autoSkipPadding: 20
                    }
                },
                y: {
                    beginAtZero: false,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 10,
                            family: "'Inter', 'Noto Sans Thai', sans-serif"
                        },
                        color: '#78716c',
                        callback: function(value) {
                            return value.toFixed(1);
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
    
    console.log('DO Chart updated with', filteredData.length, 'data points');
}

// ฟังก์ชันกรองข้อมูลตามช่วงเวลา
function filterDataByPeriod(data, period) {
    const now = new Date();
    let cutoffDate;
    
    if (period === 'day') {
        // เอาข้อมูล 24 ชั่วโมงล่าสุด
        cutoffDate = new Date(now.getTime() - (24 * 60 * 60 * 1000));
    } else if (period === 'month') {
        // เอาข้อมูล 30 วันล่าสุด
        cutoffDate = new Date(now.getTime() - (30 * 24 * 60 * 60 * 1000));
    }
    
    return data.filter(item => {
        const itemDate = new Date(item.created_at);
        return itemDate >= cutoffDate;
    });
}

// จัดการปุ่มสลับช่วงเวลา
document.addEventListener('DOMContentLoaded', () => {
    const periodButtons = document.querySelectorAll('[data-period]');
    
    periodButtons.forEach(button => {
        button.addEventListener('click', async () => {
            // อัปเดต UI ปุ่ม
            periodButtons.forEach(btn => {
                btn.classList.remove('bg-white', 'shadow-sm', 'text-primary');
                btn.classList.add('text-stone-500', 'hover:bg-white/50');
            });
            
            button.classList.remove('text-stone-500', 'hover:bg-white/50');
            button.classList.add('bg-white', 'shadow-sm', 'text-primary');
            
            // เปลี่ยนช่วงเวลา
            currentPeriod = button.dataset.period;
            
            // โหลดข้อมูลใหม่
            try {
                const data = await API.getWaterQuality();
                const doData = data.find(item => item.id === 11);
                if (doData && doData.value) {
                    updateDOChart(doData.value);
                }
            } catch (error) {
                console.error('Error updating chart:', error);
            }
        });
    });
});

// ฟังก์ชันอัปเดตกราฟราคา (ใช้กับ Shrimp Price API)
async function loadShrimpPriceChart() {
    try {
        const res = await fetch("shrimp_price_proxy.php");
        const json = await res.json();

        console.log("Price API DATA:", json);

        const rows = json.data || [];

        const labels = [];
        const price50 = [];
        const price70 = [];

        // เรียงวันที่
        rows.sort((a, b) => new Date(a.data_date) - new Date(b.data_date));

        rows.forEach(r => {
            const date = new Date(r.data_date).toLocaleDateString('th-TH', { 
                day: 'numeric', 
                month: 'short' 
            });
            const name = r.product_name || "";
            const price = Number(r.day_price);

            if (!labels.includes(date)) {
                labels.push(date);
            }

            if (name.includes("50")) {
                price50.push(price);
            }

            if (name.includes("70")) {
                price70.push(price);
            }
        });

        document.getElementById("price-loading").style.display = "none";

        renderPriceChart(labels, price50, price70);

    } catch (e) {
        console.error("Price chart error:", e);
    }
}

function renderPriceChart(labels, p50, p70) {
    const ctx = document.getElementById("priceChart").getContext('2d');
    
    new Chart(ctx, {
        type: "line",
        data: {
            labels,
            datasets: [
                {
                    label: "50 ตัว/กก.",
                    data: p50,
                    borderColor: "#ff8021",
                    backgroundColor: "rgba(255, 128, 33, 0.15)",
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: "#ff8021",
                    pointBorderColor: "#fff",
                    pointBorderWidth: 2
                },
                {
                    label: "70 ตัว/กก.",
                    data: p70,
                    borderColor: "#fdba74",
                    backgroundColor: "rgba(253, 186, 116, 0.12)",
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: "#fdba74",
                    pointBorderColor: "#fff",
                    pointBorderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(29, 19, 12, 0.9)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#ff8021',
                    borderWidth: 1
                }
            },
            scales: {
                x: { 
                    grid: {
                        display: false
                    },
                    ticks: { 
                        font: { size: 10 }, 
                        color: "#78716c",
                        maxRotation: 0,
                        autoSkipPadding: 20
                    }
                },
                y: { 
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: { 
                        font: { size: 10 }, 
                        color: "#78716c",
                        callback: function(value) {
                            return value.toLocaleString('th-TH') + ' ฿';
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// โหลดกราฟราคาเมื่อเริ่มต้น
document.addEventListener('DOMContentLoaded', () => {
    loadShrimpPriceChart();
});