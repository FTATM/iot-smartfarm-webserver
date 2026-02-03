// API Configuration for External Backend
const API_CONFIG = {
    // ===================================
    // ตั้งค่า API Endpoint ของคุณที่นี่
    // ===================================
    BASE_URL: 'http://your-backend-api.com/api', // เปลี่ยนเป็น URL ของ Backend คุณ
    
    // API Endpoints
    ENDPOINTS: {
        // ข้อมูลเซ็นเซอร์
        SENSORS_CURRENT: '/sensors/current',           // ค่าเซ็นเซอร์ปัจจุบัน
        SENSORS_HISTORY: '/sensors/history',           // ประวัติค่าเซ็นเซอร์
        
        // ข้อมูลฟาร์ม
        FARM_CONFIG: '/farm/config',                   // การตั้งค่าฟาร์ม
        FARM_STATUS: '/farm/status',                   // สถานะฟาร์ม
        
        // ข้อมูลการให้อาหาร
        FEEDING_SCHEDULE: '/feeding/schedule',         // ตารางให้อาหาร
        FEEDING_HISTORY: '/feeding/history',           // ประวัติการให้อาหาร
        
        // ทรัพยากร
        RESOURCES: '/resources/usage',                 // การใช้ทรัพยากร
        
        // ราคาตลาด
        MARKET_PRICE: '/market/price',                 // ราคาตลาด
        
        // สถานะระบบ
        SYSTEM_STATUS: '/system/status',               // สถานะระบบ
    },
    
    // Header configuration
    HEADERS: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        // เพิ่ม Authorization header ถ้าจำเป็น
        // 'Authorization': 'Bearer YOUR_TOKEN_HERE'
    },
    
    // Request timeout (milliseconds)
    TIMEOUT: 10000,
    
    // Retry configuration
    RETRY: {
        enabled: true,
        maxAttempts: 3,
        delay: 1000
    }
};

// ===================================
// รูปแบบข้อมูลที่ Backend ต้องส่งกลับ
// ===================================

/*
1. ข้อมูลเซ็นเซอร์ปัจจุบัน - GET /sensors/current
Response:
{
    "success": true,
    "data": {
        "do": {
            "value": 5.2,
            "unit": "mg/L",
            "timestamp": "2024-01-27 14:35:20",
            "status": "normal"  // normal, warning, critical
        },
        "ph": {
            "value": 8.1,
            "unit": "pH",
            "timestamp": "2024-01-27 14:35:20",
            "status": "warning"
        },
        "ec": {
            "value": 42.5,
            "unit": "mS/cm",
            "timestamp": "2024-01-27 14:35:20",
            "status": "normal"
        },
        "temp": {
            "value": 29.5,
            "unit": "°C",
            "timestamp": "2024-01-27 14:35:20",
            "status": "normal",
            "trend": "increasing"  // stable, increasing, decreasing
        }
    },
    "timestamp": "2024-01-27 14:35:20"
}

2. ประวัติเซ็นเซอร์ - GET /sensors/history?sensor=do&period=day
Response:
{
    "success": true,
    "data": [
        {
            "timestamp": "2024-01-27 00:00:00",
            "value": 5.5,
            "feeding": false  // true ถ้าเป็นเวลาให้อาหาร
        },
        {
            "timestamp": "2024-01-27 01:00:00",
            "value": 5.3,
            "feeding": false
        },
        {
            "timestamp": "2024-01-27 06:00:00",
            "value": 3.8,
            "feeding": true  // ช่วงให้อาหาร
        }
        // ... 24 ชั่วโมง
    ]
}

3. ข้อมูลฟาร์ม - GET /farm/config
Response:
{
    "success": true,
    "data": {
        "farm_name": "บ่อเลี้ยงกุ้งขาว #1",
        "pond_id": "POND-001",
        "start_date": "2024-01-06",
        "current_age_days": 21,
        "harvest_cycle_days": 120,
        "expected_harvest_date": "2024-05-05",
        "days_to_harvest": 99,
        "completion_percentage": 17.5,
        "current_stage": {
            "name": "ระยะอนุบาล",
            "name_en": "Nursery Stage",
            "day_start": 1,
            "day_end": 20,
            "color": "#22c55e",
            "priority": "critical"
        }
    }
}

4. ตารางให้อาหาร - GET /feeding/schedule
Response:
{
    "success": true,
    "data": {
        "meals_per_day": 4,
        "increment_per_meal": 200,  // กรัม
        "total_daily": 2800,         // กรัม
        "feeding_times": ["06:00", "12:00", "15:00", "18:00"],
        "last_feeding": {
            "time": "12:00",
            "amount": 700,
            "status": "completed"
        }
    }
}

5. การใช้ทรัพยากร - GET /resources/usage
Response:
{
    "success": true,
    "data": {
        "water": {
            "value": 12.8,
            "unit": "m³",
            "cost": 256,  // บาท (optional)
            "status": "normal"
        },
        "electricity": {
            "value": 145,
            "unit": "kWh",
            "cost": 580,  // บาท (optional)
            "status": "normal"
        }
    }
}

6. ราคาตลาด - GET /market/price?days=10
Response:
{
    "success": true,
    "data": [
        {
            "date": "2024-01-18",
            "size_50": 180,  // บาท/กก. (50 ตัว/กก.)
            "size_70": 120   // บาท/กก. (70 ตัว/กก.)
        },
        {
            "date": "2024-01-19",
            "size_50": 178,
            "size_70": 119
        }
        // ... 10 วัน
    ]
}

7. สถานะระบบ - GET /system/status
Response:
{
    "success": true,
    "data": {
        "sensors": {
            "total": 12,
            "active": 12,
            "inactive": 0,
            "status": "normal"  // normal, warning, error
        },
        "aerator": {
            "status": "active",  // active, inactive
            "uptime_hours": 48.5
        }
    }
}

8. Error Response (ทุก Endpoint)
Response:
{
    "success": false,
    "error": "Error message here",
    "code": "ERROR_CODE"  // optional
}
*/

// ===================================
// API Service Functions
// ===================================

const BackendAPI = {
    /**
     * Generic fetch wrapper with error handling and retry
     */
    async fetch(endpoint, options = {}) {
        const url = `${API_CONFIG.BASE_URL}${endpoint}`;
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), API_CONFIG.TIMEOUT);
        
        try {
            const response = await fetch(url, {
                ...options,
                headers: {
                    ...API_CONFIG.HEADERS,
                    ...options.headers
                },
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.error || 'Unknown error');
            }
            
            return data;
            
        } catch (error) {
            clearTimeout(timeoutId);
            
            if (error.name === 'AbortError') {
                throw new Error('Request timeout');
            }
            
            // Retry logic
            if (API_CONFIG.RETRY.enabled && options._retryCount < API_CONFIG.RETRY.maxAttempts) {
                await new Promise(resolve => setTimeout(resolve, API_CONFIG.RETRY.delay));
                return this.fetch(endpoint, {
                    ...options,
                    _retryCount: (options._retryCount || 0) + 1
                });
            }
            
            throw error;
        }
    },
    
    /**
     * Get current sensor readings
     */
    async getCurrentSensors() {
        return await this.fetch(API_CONFIG.ENDPOINTS.SENSORS_CURRENT);
    },
    
    /**
     * Get sensor history
     * @param {string} sensor - Sensor type (do, ph, ec, temp)
     * @param {string} period - Time period (day, week, month)
     */
    async getSensorHistory(sensor = 'do', period = 'day') {
        return await this.fetch(`${API_CONFIG.ENDPOINTS.SENSORS_HISTORY}?sensor=${sensor}&period=${period}`);
    },
    
    /**
     * Get farm configuration
     */
    async getFarmConfig() {
        return await this.fetch(API_CONFIG.ENDPOINTS.FARM_CONFIG);
    },
    
    /**
     * Get feeding schedule
     */
    async getFeedingSchedule() {
        return await this.fetch(API_CONFIG.ENDPOINTS.FEEDING_SCHEDULE);
    },
    
    /**
     * Get resource usage
     */
    async getResourceUsage() {
        return await this.fetch(API_CONFIG.ENDPOINTS.RESOURCES);
    },
    
    /**
     * Get market prices
     * @param {number} days - Number of days to fetch
     */
    async getMarketPrice(days = 10) {
        return await this.fetch(`${API_CONFIG.ENDPOINTS.MARKET_PRICE}?days=${days}`);
    },
    
    /**
     * Get system status
     */
    async getSystemStatus() {
        return await this.fetch(API_CONFIG.ENDPOINTS.SYSTEM_STATUS);
    }
};

// ===================================
// Export for use in other modules
// ===================================
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { API_CONFIG, BackendAPI };
}