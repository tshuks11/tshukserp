<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Edit User</h1>
</div>

<form method="POST" action="/users/<?= $user['id'] ?>" class="form">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    
    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" value="<?= Security::escape($user['name']) ?>" required>
    </div>
    
    <div class="form-group">
        <label>Email</label>
        <input type="email" value="<?= Security::escape($user['email']) ?>" disabled>
    </div>
    
    <div class="form-group">
        <label>Role</label>
        <select name="role_id" required>
            <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id'] ?>" <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>><?= Security::escape($role['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Status</label>
        <select name="status" required>
            <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            <option value="suspended" <?= $user['status'] == 'suspended' ? 'selected' : '' ?>>Suspended</option>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Update User</button>
</form>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
