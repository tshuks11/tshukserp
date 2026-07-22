<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Invoices</h1>
    <a href="/invoices/from-quotation" class="btn btn-success">From Quotation</a>
    <a href="/invoices/create" class="btn btn-primary">New Invoice</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Reference</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invoices as $i): ?>
        <tr>
            <td><?= Security::escape($i['reference']) ?></td>
            <td><?= Security::escape($i['customer_name']) ?></td>
            <td><?= formatCurrency($i['total_amount']) ?></td>
            <td><span class="badge badge-<?= $i['status'] ?>"><?= ucfirst(str_replace('_', ' ', $i['status'])) ?></span></td>
            <td><?= formatDate($i['issue_date']) ?></td>
            <td><a href="/invoices/<?= $i['id'] ?>">View</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
