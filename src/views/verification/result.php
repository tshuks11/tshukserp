<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Verification Result</title>
    <link rel="stylesheet" href="/css/admin.css">
    <style>
        .verification-container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="verification-container">
        <h1><?= APP_NAME ?> - Document Verification</h1>
        
        <?php if ($verified): ?>
            <div style="background: #e8f5e9; border: 1px solid #4caf50; border-radius: 5px; padding: 20px; margin: 20px 0;">
                <h2 style="color: #2e7d32;">✓ Document Verified</h2>
                <p style="margin: 15px 0;"><strong>Type:</strong> <?= ucfirst($type) ?></p>
                <p style="margin: 15px 0;"><strong>Reference:</strong> <?= Security::escape($document['reference']) ?></p>
                <p style="margin: 15px 0;"><strong>Customer:</strong> <?= Security::escape($document['customer_name']) ?></p>
                <p style="margin: 15px 0;"><strong>Total Amount:</strong> <?= formatCurrency($document['total_amount']) ?></p>
                <p style="margin: 15px 0;"><strong>Status:</strong> <?= ucfirst($document['status']) ?></p>
                <p style="margin: 15px 0;"><strong>Date:</strong> <?= formatDateTime($document['created_at']) ?></p>
                
                <h3 style="margin-top: 30px;">Items</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($document['items'] as $item): ?>
                        <tr>
                            <td><?= Security::escape($item['product_name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= formatCurrency($item['unit_price']) ?></td>
                            <td><?= formatCurrency($item['line_total']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="background: #ffebee; border: 1px solid #ef5350; border-radius: 5px; padding: 20px; margin: 20px 0;">
                <h2 style="color: #c62828;">✗ Invalid Verification Code</h2>
                <p style="margin: 15px 0;">The verification code you entered could not be found. Please check and try again.</p>
                <a href="/verify" class="btn btn-primary" style="margin-top: 15px;">Try Again</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
