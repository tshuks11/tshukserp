<?php
/**
 * TshuksERP - Main Application Entry Point
 */

session_start();

define('BASE_PATH', dirname(dirname(__FILE__)));
define('PUBLIC_PATH', __DIR__);
define('SRC_PATH', BASE_PATH . '/src');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/logs/errors.log');

if (!file_exists(SRC_PATH . '/config/database.php')) {
    header('Location: /install.php');
    exit;
}

require_once SRC_PATH . '/config/database.php';
require_once SRC_PATH . '/config/constants.php';
require_once SRC_PATH . '/utils/Security.php';
require_once SRC_PATH . '/utils/Database.php';
require_once SRC_PATH . '/utils/Helper.php';
require_once SRC_PATH . '/auth/SessionGuard.php';
require_once SRC_PATH . '/Router.php';

require_once SRC_PATH . '/auth/User.php';
require_once SRC_PATH . '/auth/Role.php';
require_once SRC_PATH . '/models/Customer.php';
require_once SRC_PATH . '/models/Product.php';
require_once SRC_PATH . '/models/Quotation.php';
require_once SRC_PATH . '/models/Invoice.php';
require_once SRC_PATH . '/models/Payment.php';
require_once SRC_PATH . '/models/Expense.php';
require_once SRC_PATH . '/controllers/BaseController.php';
require_once SRC_PATH . '/controllers/AuthController.php';
require_once SRC_PATH . '/controllers/DashboardController.php';
require_once SRC_PATH . '/controllers/QuotationController.php';
require_once SRC_PATH . '/controllers/InvoiceController.php';
require_once SRC_PATH . '/controllers/PaymentController.php';
require_once SRC_PATH . '/controllers/ExpenseController.php';
require_once SRC_PATH . '/controllers/InventoryController.php';
require_once SRC_PATH . '/controllers/ReportController.php';

$sessionGuard = new SessionGuard();
$sessionGuard->guard();

$router = new Router();
$router->dispatch();
