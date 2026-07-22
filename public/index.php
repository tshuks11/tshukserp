<?php
/**
 * TshuksERP - Main Application Entry Point
 */

session_start();

// Define base paths
define('BASE_PATH', dirname(dirname(__FILE__)));
define('PUBLIC_PATH', __DIR__);
define('SRC_PATH', BASE_PATH . '/src');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/logs/errors.log');

// Load configuration
if (!file_exists(SRC_PATH . '/config/database.php')) {
    header('Location: /install.php');
    exit;
}

require_once SRC_PATH . '/config/database.php';
require_once SRC_PATH . '/config/constants.php';
require_once SRC_PATH . '/utils/Security.php';
require_once SRC_PATH . '/utils/Database.php';
require_once SRC_PATH . '/auth/SessionGuard.php';

// Initialize application
$sessionGuard = new SessionGuard();
$sessionGuard->guard();

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove query string
if (strpos($request_uri, '?') !== false) {
    $request_uri = substr($request_uri, 0, strpos($request_uri, '?'));
}

// Route to appropriate controller
$routes = require SRC_PATH . '/routes.php';

echo "TshuksERP - Welcome";
?>
