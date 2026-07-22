<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Create User</h1>
</div>

<form method="POST" action="/users" class="form">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    
    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" required>
    </div>
    
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    
    <div class="form-group">
        <label>Role</label>
        <select name="role_id" required>
            <option value="">Select a role</option>
            <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id'] ?>"><?= Security::escape($role['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Create User</button>
</form>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
