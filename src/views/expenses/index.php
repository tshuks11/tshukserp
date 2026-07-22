<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Expenses</h1>
    <a href="/expenses/create" class="btn btn-primary">New Expense</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Category</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($expenses as $e): ?>
        <tr>
            <td><?= Security::escape($e['category']) ?></td>
            <td><?= Security::escape($e['description'] ?? '-') ?></td>
            <td><?= formatCurrency($e['amount']) ?></td>
            <td><?= formatDate($e['expense_date']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
