<?php include VIEWS_PATH . '/layout/admin_header.php'; ?>

<div class="page-header">
    <h1>Users</h1>
    <a href="/users/create" class="btn btn-primary">New User</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Last Login</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= Security::escape($u['name']) ?></td>
            <td><?= Security::escape($u['email']) ?></td>
            <td><?= ucfirst($u['role']) ?></td>
            <td><span class="badge badge-<?= $u['status'] ?>"><?= ucfirst($u['status']) ?></span></td>
            <td><?= $u['last_login'] ? formatDateTime($u['last_login']) : '-' ?></td>
            <td><a href="/users/<?= $u['id'] ?>/edit">Edit</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include VIEWS_PATH . '/layout/admin_footer.php'; ?>
