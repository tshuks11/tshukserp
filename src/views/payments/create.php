<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Record Payment</h1>
</div>

<form method="POST" action="/payments" class="form">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    
    <div class="form-group">
        <label>Invoice</label>
        <select name="invoice_id" id="invoice_id" required onchange="updateInvoiceData()">
            <option value="">Select an invoice</option>
            <?php foreach ($invoices as $inv): ?>
            <option value="<?= $inv['id'] ?>" data-customer="<?= $inv['customer_id'] ?>" data-amount="<?= $inv['total_amount'] ?>">
                <?= Security::escape($inv['reference']) ?> - <?= formatCurrency($inv['total_amount']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Customer ID</label>
        <input type="hidden" name="customer_id" id="customer_id">
    </div>
    
    <div class="form-group">
        <label>Amount</label>
        <input type="number" name="amount" step="0.01" min="0" required>
    </div>
    
    <div class="form-group">
        <label>Payment Date</label>
        <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" required>
    </div>
    
    <div class="form-group">
        <label>Payment Method</label>
        <select name="payment_method" required>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cash">Cash</option>
            <option value="cheque">Cheque</option>
            <option value="credit_card">Credit Card</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Reference</label>
        <input type="text" name="reference" placeholder="e.g., cheque number">
    </div>
    
    <div class="form-group">
        <label>Notes</label>
        <textarea name="notes" rows="4"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Record Payment</button>
</form>

<script>
function updateInvoiceData() {
    const select = document.getElementById('invoice_id');
    const option = select.options[select.selectedIndex];
    document.getElementById('customer_id').value = option.dataset.customer || '';
}
</script>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
