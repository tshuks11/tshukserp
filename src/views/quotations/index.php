<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Quotations</h1>
    <a href="/quotations/create" class="btn btn-primary">New Quotation</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Reference</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quotations as $q): ?>
        <tr>
            <td><?= Security::escape($q['reference']) ?></td>
            <td><?= Security::escape($q['customer_name']) ?></td>
            <td><?= formatCurrency($q['total_amount']) ?></td>
            <td><span class="badge badge-<?= $q['status'] ?>"><?= ucfirst($q['status']) ?></span></td>
            <td><a href="/quotations/<?= $q['id'] ?>">View</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
