<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Customer Analytics</h1>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Invoices</th>
            <th>Total Spent</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($customers as $c): ?>
        <tr>
            <td><?= Security::escape($c['name']) ?></td>
            <td><?= $c['invoice_count'] ?></td>
            <td><?= formatCurrency($c['total_spent'] ?? 0) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
