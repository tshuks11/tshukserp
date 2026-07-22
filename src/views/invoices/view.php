<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Invoice #<?= Security::escape($invoice['reference']) ?></h1>
</div>

<div class="invoice-details">
    <div class="details-row">
        <div class="detail-group">
            <p><strong>Customer:</strong> <?= Security::escape($invoice['customer_name']) ?></p>
            <p><strong>Status:</strong> <span class="badge badge-<?= $invoice['status'] ?>"><?= ucfirst(str_replace('_', ' ', $invoice['status'])) ?></span></p>
        </div>
        <div class="detail-group">
            <p><strong>Total:</strong> <?= formatCurrency($invoice['total_amount']) ?></p>
            <p><strong>Issue Date:</strong> <?= formatDate($invoice['issue_date']) ?></p>
        </div>
    </div>
    
    <div class="detail-group">
        <p><strong>Verification Code:</strong> <?= Security::escape($invoice['verification_code']) ?></p>
        <img src="<?= $qrCode ?>" alt="QR Code" style="width: 150px; height: 150px; margin-top: 10px;">
    </div>
</div>

<h3>Items</h3>
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
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= Security::escape($item['product_name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= formatCurrency($item['unit_price']) ?></td>
            <td><?= formatCurrency($item['line_total']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="actions">
    <a href="/invoices" class="btn btn-secondary">Back</a>
    <button onclick="window.print()" class="btn btn-primary">Print</button>
    <a href="/payments/create?invoice=<?= $invoice['id'] ?>" class="btn btn-success">Record Payment</a>
</div>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
