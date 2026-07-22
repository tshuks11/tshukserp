<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Quotation #<?= Security::escape($quotation['reference']) ?></h1>
</div>

<div class="quotation-details">
    <p><strong>Customer:</strong> <?= Security::escape($quotation['customer_name']) ?></p>
    <p><strong>Status:</strong> <span class="badge badge-<?= $quotation['status'] ?>"><?= ucfirst($quotation['status']) ?></span></p>
    <p><strong>Total:</strong> <?= formatCurrency($quotation['total_amount']) ?></p>
    <p><strong>Expiry Date:</strong> <?= formatDate($quotation['expiry_date']) ?></p>
    <p><strong>Verification Code:</strong> <?= Security::escape($quotation['verification_code']) ?></p>
    <p><strong>QR Code:</strong></p>
    <img src="<?= $qrCode ?>" alt="QR Code" style="width: 200px; height: 200px;">
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
            <td><?= Security::escape($item['name']) ?> (<?= Security::escape($item['sku']) ?>)</td>
            <td><?= $item['quantity'] ?></td>
            <td><?= formatCurrency($item['unit_price']) ?></td>
            <td><?= formatCurrency($item['line_total']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="actions">
    <a href="/quotations" class="btn btn-secondary">Back</a>
    <button onclick="window.print()" class="btn btn-primary">Print</button>
    <a href="/invoices/from-quotation?q=<?= $quotation['id'] ?>" class="btn btn-success">Convert to Invoice</a>
</div>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
