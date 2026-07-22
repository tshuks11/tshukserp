<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Create Quotation</h1>
</div>

<form method="POST" action="/quotations" class="form">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    
    <div class="form-group">
        <label>Customer</label>
        <select name="customer_id" required>
            <option value="">Select a customer</option>
            <?php foreach ($customers as $c): ?>
            <option value="<?= $c['id'] ?>"><?= Security::escape($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Expiry Date</label>
        <input type="date" name="expiry_date" required>
    </div>
    
    <div class="form-section">
        <h3>Items</h3>
        <div id="items-container">
            <div class="item-row">
                <select name="items[0][product_id]">
                    <option value="">Select product</option>
                    <?php foreach ($products as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= Security::escape($p['name']) ?> (<?= formatCurrency($p['price']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="items[0][quantity]" placeholder="Qty" min="1">
                <input type="number" name="items[0][unit_price]" placeholder="Price" step="0.01" min="0">
            </div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="addItemRow()">Add Item</button>
    </div>
    
    <button type="submit" class="btn btn-primary">Create Quotation</button>
</form>

<script>
let itemCount = 1;
function addItemRow() {
    const container = document.getElementById('items-container');
    const row = document.createElement('div');
    row.className = 'item-row';
    row.innerHTML = `
        <select name="items[${itemCount}][product_id]">
            <option value="">Select product</option>
            <?php foreach ($products as $p): ?>
            <option value="<?= $p['id'] ?>"><?= Security::escape($p['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="items[${itemCount}][quantity]" placeholder="Qty" min="1">
        <input type="number" name="items[${itemCount}][unit_price]" placeholder="Price" step="0.01" min="0">
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">Remove</button>
    `;
    container.appendChild(row);
    itemCount++;
}
</script>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
