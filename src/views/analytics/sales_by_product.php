<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Sales By Product</h1>
</div>

<table class="table">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Product</th>
            <th>Units Sold</th>
            <th>Revenue</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($salesData as $s): ?>
        <tr>
            <td><?= Security::escape($s['sku']) ?></td>
            <td><?= Security::escape($s['name']) ?></td>
            <td><?= $s['total_qty'] ?? 0 ?></td>
            <td><?= formatCurrency($s['total_amount'] ?? 0) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
