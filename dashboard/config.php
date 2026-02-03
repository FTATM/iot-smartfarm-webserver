<?php 
/**
 * DashboardBuilder
 *
 * @author Diginix Technologies www.diginixtech.com
 * Support <support@dashboardbuider.net> - https://www.dashboardbuilder.net
 * @copyright (C) 2016-2024 Dashboardbuilder.net
 * @version 7.1
 * @license: Must read and agree LICENSE.txt before use.
 */

define('FOLDER', "../");
define("DATA",FOLDER."data/");
define("STORE",FOLDER."store/");

if ($_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define('ENVIRONMENT', 'development');
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    define('ENVIRONMENT', 'production');
}

// Timezone
date_default_timezone_set('Asia/Bangkok');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'shrimp_farm');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// API Configuration
define('API_BASE_URL', '/api');
define('API_TIMEOUT', 30); // seconds

// Application Settings
define('APP_NAME', 'Shrimp Farm Dashboard');
define('APP_VERSION', '2.5');
define('DEFAULT_FARM_ID', 1);

// Update Intervals (milliseconds)
define('UPDATE_INTERVAL_METRICS', 30000);  // 30 seconds
define('UPDATE_INTERVAL_CHARTS', 300000);  // 5 minutes
define('UPDATE_INTERVAL_STATUS', 60000);   // 1 minute

// Sensor Thresholds
define('DO_MIN', 5.0);
define('DO_WARNING', 4.0);
define('PH_MIN', 7.5);
define('PH_MAX', 8.5);
define('TEMP_MIN', 28);
define('TEMP_MAX', 32);

// CORS Settings
define('CORS_ALLOWED_ORIGINS', '*'); // Change in production
define('CORS_ALLOWED_METHODS', 'GET, POST, OPTIONS');
define('CORS_ALLOWED_HEADERS', 'Content-Type, Authorization');

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_NAME', 'SHRIMP_DASHBOARD_SESSION');

// Paths
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_PATH', BASE_PATH . '/assets');
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('API_PATH', BASE_PATH . '/api');

// URLs
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
define('BASE_URL', $protocol . '://' . $host);
define('ASSETS_URL', BASE_URL . '/assets');

// Auto-load helper functions
if (file_exists(INCLUDES_PATH . '/functions.php')) {
    require_once INCLUDES_PATH . '/functions.php';
}

// Auto-load database class
if (file_exists(INCLUDES_PATH . '/Database.php')) {
    require_once INCLUDES_PATH . '/Database.php';
}