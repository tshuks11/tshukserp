<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Record Expense</h1>
</div>

<form method="POST" action="/expenses" class="form">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    
    <div class="form-group">
        <label>Category</label>
        <input type="text" name="category" placeholder="e.g., Travel, Accommodation" required>
    </div>
    
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4"></textarea>
    </div>
    
    <div class="form-group">
        <label>Amount</label>
        <input type="number" name="amount" step="0.01" min="0" required>
    </div>
    
    <div class="form-group">
        <label>Date</label>
        <input type="date" name="expense_date" value="<?= date('Y-m-d') ?>" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Record Expense</button>
</form>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
