<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Payments</h1>
    <a href="/payments/create" class="btn btn-primary">Record Payment</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Invoice</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($payments as $p): ?>
        <tr>
            <td><?= Security::escape($p['invoice_ref']) ?></td>
            <td><?= Security::escape($p['customer_name']) ?></td>
            <td><?= formatCurrency($p['amount']) ?></td>
            <td><?= ucfirst(str_replace('_', ' ', $p['payment_method'])) ?></td>
            <td><?= formatDate($p['payment_date']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
