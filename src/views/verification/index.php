<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Document Verification</title>
    <link rel="stylesheet" href="/css/admin.css">
    <style>
        .verification-container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .verification-form { margin: 20px 0; }
        .search-box { display: flex; gap: 10px; margin-bottom: 20px; }
        .search-box input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="verification-container">
        <h1><?= APP_NAME ?> - Document Verification</h1>
        <p style="color: #666; margin-bottom: 20px;">Enter the verification code or scan the QR code to verify a document.</p>
        
        <div class="verification-form">
            <form method="GET" action="/verify">
                <div class="search-box">
                    <input type="text" name="code" placeholder="Enter verification code" autofocus>
                    <button type="submit" class="btn btn-primary">Verify</button>
                </div>
            </form>
        </div>
        
        <?php if (isset($document) && $document): ?>
            <div style="background: #e8f5e9; border: 1px solid #4caf50; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <h2><?= ucfirst($document['type']) ?> Verified ✓</h2>
                <p><strong>Reference:</strong> <?= Security::escape($document['reference']) ?></p>
                <p><strong>Amount:</strong> <?= formatCurrency($document['total_amount']) ?></p>
                <p><strong>Date:</strong> <?= formatDateTime($document['created_at']) ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
