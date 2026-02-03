// Dashboard Configuration
const CONFIG = {
    apiBaseUrl: '/dashboard/api',
    refreshInterval: 30000,

    
    // Chart Configuration
    charts: {
        doTrend: {
            optimalMin: 5.0,
            warningMin: 3.0,
            criticalMin: 0.0,
            optimalColor: '#22c55e',
            warningColor: '#f59e0b',
            criticalColor: '#ef4444',
        },
        priceTrend: {
            size50Color: '#ff8021',
            size70Color: 'rgba(255, 128, 33, 0.4)',
        }
    },
    
    // Metric Thresholds
    thresholds: {
        do: {
            optimal: 5.0,
            warning: 3.0,
            critical: 2.0,
            unit: 'mg/L',
            name: 'ค่าออกซิเจน (DO)'
        },
        ph: {
            optimalMin: 7.5,
            optimalMax: 8.5,
            warningMin: 7.0,
            warningMax: 9.0,
            unit: 'pH',
            name: 'กรด-ด่าง (pH)'
        },
        ec: {
            optimalMin: 35,
            optimalMax: 50,
            warningMin: 30,
            warningMax: 55,
            unit: 'mS/cm',
            name: 'ความนำไฟฟ้า (EC)'
        },
        temp: {
            optimalMin: 28,
            optimalMax: 32,
            warningMin: 26,
            warningMax: 34,
            unit: '°C',
            name: 'อุณหภูมิ (Temp)'
        }
    },
    
    // Status Messages
    statusMessages: {
        normal: 'ปกติ',
        warning: 'เฝ้าระวัง',
        critical: 'วิกฤต',
        stable: 'คงที่',
        increasing: 'เพิ่มขึ้น',
        decreasing: 'ลดลง'
    },
    
    // Date Format
    dateFormat: {
        full: 'dd MMM yyyy',
        short: 'dd MMM',
        time: 'HH:mm:ss'
    }
};

// Helper Functions
const Utils = {
    // Format number with commas
    formatNumber(num, decimals = 1) {
        return num.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    
    // Get status based on value and thresholds
    getStatus(value, metric) {
        const threshold = CONFIG.thresholds[metric];
        
        if (!threshold) return 'normal';
        
        if (metric === 'do') {
            if (value >= threshold.optimal) return 'normal';
            if (value >= threshold.warning) return 'warning';
            return 'critical';
        }
        
        if (metric === 'ph' || metric === 'ec' || metric === 'temp') {
            if (value >= threshold.optimalMin && value <= threshold.optimalMax) {
                return 'normal';
            }
            if (value >= threshold.warningMin && value <= threshold.warningMax) {
                return 'warning';
            }
            return 'critical';
        }
        
        return 'normal';
    },
    
    // Get status color
    getStatusColor(status) {
        const colors = {
            normal: 'success',
            warning: 'warning',
            critical: 'danger'
        };
        return colors[status] || 'success';
    },
    
    // Format date in Thai
    formatDateThai(dateString) {
        const thaiMonths = [
            'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
            'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
        ];
        
        const date = new Date(dateString);
        const day = date.getDate();
        const month = thaiMonths[date.getMonth()];
        const year = date.getFullYear() + 543; // Convert to Buddhist year
        
        return `${day} ${month} ${year}`;
    },
    
    // Calculate shrimp age in days
    calculateShrimpAge(startDate) {
        const start = new Date(startDate);
        const today = new Date();
        const diffTime = Math.abs(today - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    },
    
    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { CONFIG, Utils };
}