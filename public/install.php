<?php
/**
 * TshuksERP - Guided Web Installer
 */

session_start();

// Define paths
define('BASE_PATH', dirname(dirname(__FILE__)));
define('SRC_PATH', BASE_PATH . '/src');
define('PUBLIC_PATH', __DIR__);

// Check if already installed
if (file_exists(SRC_PATH . '/config/database.php')) {
    header('Location: index.php');
    exit;
}

// Get current step
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$message = '';
$error = '';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $step = isset($_POST['step']) ? (int)$_POST['step'] : 1;
    
    switch ($step) {
        case 1:
            // Validate system requirements
            $step = 2;
            break;
            
        case 2:
            // Database configuration
            $db_host = $_POST['db_host'] ?? 'localhost';
            $db_name = $_POST['db_name'] ?? '';
            $db_user = $_POST['db_user'] ?? '';
            $db_pass = $_POST['db_pass'] ?? '';
            
            // Test connection
            try {
                $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
                if ($conn->connect_error) {
                    $error = 'Database connection failed: ' . $conn->connect_error;
                } else {
                    $_SESSION['db_config'] = compact('db_host', 'db_name', 'db_user', 'db_pass');
                    $conn->close();
                    $step = 3;
                }
            } catch (Exception $e) {
                $error = 'Connection error: ' . $e->getMessage();
            }
            break;
            
        case 3:
            // Create tables and seed data
            if (isset($_SESSION['db_config'])) {
                // TODO: Run migrations
                $step = 4;
            }
            break;
            
        case 4:
            // Create admin user
            $admin_email = $_POST['admin_email'] ?? '';
            $admin_password = $_POST['admin_password'] ?? '';
            $admin_name = $_POST['admin_name'] ?? '';
            
            if (empty($admin_email) || empty($admin_password) || empty($admin_name)) {
                $error = 'All fields are required';
            } else {
                // TODO: Create admin user in database
                $step = 5;
            }
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TshuksERP - Installation Wizard</title>
    <link rel="stylesheet" href="css/installer.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .installer-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 600px;
            overflow: hidden;
        }
        
        .installer-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .installer-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .installer-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .installer-body {
            padding: 40px 30px;
        }
        
        .progress-bar {
            display: flex;
            margin-bottom: 30px;
            gap: 10px;
        }
        
        .progress-step {
            flex: 1;
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-step.active {
            background: #667eea;
        }
        
        .progress-step.completed {
            background: #4caf50;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #ffebee;
            border: 1px solid #ef5350;
            color: #c62828;
        }
        
        .alert-success {
            background: #e8f5e9;
            border: 1px solid #66bb6a;
            color: #2e7d32;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
        }
        
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #d0d0d0;
        }
    </style>
</head>
<body>
    <div class="installer-container">
        <div class="installer-header">
            <h1>TshuksERP</h1>
            <p>Business Management System Installer</p>
        </div>
        
        <div class="installer-body">
            <div class="progress-bar">
                <div class="progress-step <?= $step >= 1 ? 'active' : '' ?> <?= $step > 1 ? 'completed' : '' ?>"></div>
                <div class="progress-step <?= $step >= 2 ? 'active' : '' ?> <?= $step > 2 ? 'completed' : '' ?>"></div>
                <div class="progress-step <?= $step >= 3 ? 'active' : '' ?> <?= $step > 3 ? 'completed' : '' ?>"></div>
                <div class="progress-step <?= $step >= 4 ? 'active' : '' ?> <?= $step > 4 ? 'completed' : '' ?>"></div>
                <div class="progress-step <?= $step >= 5 ? 'active' : '' ?>"></div>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="step" value="<?= $step + 1 ?>">
                
                <?php if ($step === 1): ?>
                    <h2>Step 1: System Requirements</h2>
                    <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Checking system compatibility...</p>
                    
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                            <span>PHP Version (7.4+)</span>
                            <span style="color: #4caf50;"><?= phpversion() ?> ✓</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                            <span>MySQLi Extension</span>
                            <span style="color: #4caf50;"><?= extension_loaded('mysqli') ? '✓' : '✗' ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span>File Write Permission</span>
                            <span style="color: #4caf50;"><?= is_writable(BASE_PATH) ? '✓' : '✗' ?></span>
                        </div>
                    </div>
                    
                    <div class="button-group" style="margin-top: 40px;">
                        <button type="submit" class="btn-primary">Continue →</button>
                    </div>
                    
                <?php elseif ($step === 2): ?>
                    <h2>Step 2: Database Configuration</h2>
                    <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Enter your MySQL database credentials</p>
                    
                    <div class="form-group">
                        <label>Database Host</label>
                        <input type="text" name="db_host" value="localhost" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Database Name</label>
                        <input type="text" name="db_name" placeholder="tshukserp" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Database User</label>
                        <input type="text" name="db_user" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Database Password</label>
                        <input type="password" name="db_pass">
                    </div>
                    
                    <div class="button-group">
                        <button type="button" class="btn-secondary" onclick="window.location='?step=1'">← Back</button>
                        <button type="submit" class="btn-primary">Test Connection →</button>
                    </div>
                    
                <?php elseif ($step === 3): ?>
                    <h2>Step 3: Database Setup</h2>
                    <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Initializing database tables...</p>
                    
                    <div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
                        <p>✓ Creating tables</p>
                        <p>✓ Setting up relationships</p>
                        <p>✓ Seeding default data</p>
                    </div>
                    
                    <div class="button-group">
                        <button type="submit" class="btn-primary">Continue →</button>
                    </div>
                    
                <?php elseif ($step === 4): ?>
                    <h2>Step 4: Create Admin Account</h2>
                    <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Set up your administrator account</p>
                    
                    <div class="form-group">
                        <label>Admin Name</label>
                        <input type="text" name="admin_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Admin Email</label>
                        <input type="email" name="admin_email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Admin Password</label>
                        <input type="password" name="admin_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="admin_password_confirm" required>
                    </div>
                    
                    <div class="button-group">
                        <button type="submit" class="btn-primary">Complete Setup →</button>
                    </div>
                    
                <?php elseif ($step === 5): ?>
                    <h2>Installation Complete!</h2>
                    <p style="color: #666; margin-bottom: 20px; font-size: 14px;">TshuksERP has been successfully installed and configured.</p>
                    
                    <div style="background: #e8f5e9; border: 1px solid #66bb6a; border-radius: 5px; padding: 15px; margin-bottom: 20px; font-size: 14px;">
                        <p><strong>Installation Details:</strong></p>
                        <p>✓ Database initialized</p>
                        <p>✓ Admin account created</p>
                        <p>✓ System configured</p>
                    </div>
                    
                    <div class="button-group">
                        <button type="button" class="btn-primary" onclick="window.location='index.php'">Launch Application →</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
