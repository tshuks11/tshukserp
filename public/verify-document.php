<?php
/**
 * TshuksERP - Public Document Verification Page
 */

session_start();

// Define paths
define('BASE_PATH', dirname(dirname(__FILE__)));
define('SRC_PATH', BASE_PATH . '/src');

// Check if system is installed
if (!file_exists(SRC_PATH . '/config/database.php')) {
    die('System not installed');
}

require_once SRC_PATH . '/config/database.php';
require_once SRC_PATH . '/utils/Security.php';
require_once SRC_PATH . '/utils/Database.php';

$verification_code = isset($_GET['code']) ? Security::sanitizeInput($_GET['code']) : '';
$document = null;
$found = false;

if (!empty($verification_code)) {
    // TODO: Query print_logs table to find document
    $found = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Verification - TshuksERP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
            padding: 40px;
            text-align: center;
        }
        
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .search-form {
            margin-bottom: 30px;
        }
        
        .search-form input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .search-form button {
            width: 100%;
            padding: 12px 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .search-form button:hover {
            background: #5568d3;
        }
        
        .result {
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        
        .verified {
            border-left: 4px solid #4caf50;
            background: #f1f8f6;
        }
        
        .not-found {
            border-left: 4px solid #ef5350;
            background: #fef5f5;
        }
        
        .icon {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .status {
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .details {
            text-align: left;
            font-size: 13px;
            color: #666;
        }
        
        .details-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .details-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">TshuksERP</div>
        <h1>Document Verification</h1>
        <p class="subtitle">Enter your verification code to confirm document authenticity</p>
        
        <form class="search-form" method="GET">
            <input type="text" name="code" placeholder="Enter verification code..." value="<?= htmlspecialchars($verification_code) ?>" required>
            <button type="submit">Verify Document</button>
        </form>
        
        <?php if (!empty($verification_code)): ?>
            <?php if ($found): ?>
                <div class="result verified">
                    <div class="icon">✓</div>
                    <div class="status" style="color: #2e7d32;">Document Verified</div>
                    <div class="details">
                        <div class="details-row">
                            <span>Document Type:</span>
                            <span>Invoice #INV-2024-001</span>
                        </div>
                        <div class="details-row">
                            <span>Date Issued:</span>
                            <span>January 15, 2024</span>
                        </div>
                        <div class="details-row">
                            <span>Issued By:</span>
                            <span>TshuksERP System</span>
                        </div>
                        <div class="details-row">
                            <span>Verification Code:</span>
                            <span><?= htmlspecialchars($verification_code) ?></span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="result not-found">
                    <div class="icon">✗</div>
                    <div class="status" style="color: #c62828;">Document Not Found</div>
                    <p style="font-size: 13px; margin-top: 10px; color: #666;">The verification code you entered could not be found. Please check and try again.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
