<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Inventory Analytics</h1>
</div>

<?php if (!empty($lowStock)): ?>
<div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
    <h3 style="color: #856404; margin-top: 0;">⚠️ Low Stock Alert</h3>
    <p><?= count($lowStock) ?> products have stock below 10 units.</p>
</div>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Product</th>
            <th>Current Stock</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?= Security::escape($p['sku']) ?></td>
            <td><?= Security::escape($p['name']) ?></td>
            <td><?= $p['quantity'] ?></td>
            <td>
                <?php if ($p['quantity'] < 10): ?>
                    <span style="color: #f44336; font-weight: bold;">⚠️ Low Stock</span>
                <?php elseif ($p['quantity'] < 20): ?>
                    <span style="color: #ff9800; font-weight: bold;">⚡ Medium Stock</span>
                <?php else: ?>
                    <span style="color: #4caf50;">✓ In Stock</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
