<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Company Settings</h1>
</div>

<form method="POST" action="/settings" enctype="multipart/form-data" class="form" style="max-width: 600px;">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    
    <div class="form-group">
        <label>Company Name</label>
        <input type="text" name="company_name" value="<?= Security::escape($settings['company_name'] ?? APP_NAME) ?>">
    </div>
    
    <div class="form-group">
        <label>Company Email</label>
        <input type="email" name="company_email" value="<?= Security::escape($settings['company_email'] ?? '') ?>">
    </div>
    
    <div class="form-group">
        <label>Company Phone</label>
        <input type="text" name="company_phone" value="<?= Security::escape($settings['company_phone'] ?? '') ?>">
    </div>
    
    <div class="form-group">
        <label>Company Address</label>
        <textarea name="company_address" rows="3"><?= Security::escape($settings['company_address'] ?? '') ?></textarea>
    </div>
    
    <div class="form-group">
        <label>Company Logo</label>
        <input type="file" name="company_logo" accept="image/*">
        <?php if (!empty($settings['company_logo'])): ?>
            <p style="margin-top: 10px;">Current logo:</p>
            <img src="/uploads/<?= Security::escape($settings['company_logo']) ?>" style="width: 150px; margin-top: 10px;">
        <?php endif; ?>
    </div>
    
    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
