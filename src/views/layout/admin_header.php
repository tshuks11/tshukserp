<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Admin</title>
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><?= APP_NAME ?></h2>
            </div>
            <nav class="sidebar-menu">
                <a href="/dashboard" class="menu-item">Dashboard</a>
                
                <?php if (hasPermission(PERM_MANAGE_INVENTORY)): ?>
                <a href="/inventory" class="menu-item">Inventory</a>
                <?php endif; ?>
                
                <?php if (hasPermission(PERM_CREATE_QUOTATION)): ?>
                <a href="/quotations" class="menu-item">Quotations</a>
                <?php endif; ?>
                
                <?php if (hasPermission(PERM_CREATE_INVOICE)): ?>
                <a href="/invoices" class="menu-item">Invoices</a>
                <?php endif; ?>
                
                <?php if (hasPermission(PERM_MANAGE_PAYMENTS)): ?>
                <a href="/payments" class="menu-item">Payments</a>
                <?php endif; ?>
                
                <?php if (hasPermission(PERM_VIEW_REPORTS)): ?>
                <a href="/expenses" class="menu-item">Expenses</a>
                <a href="/reports/sales" class="menu-item">Reports</a>
                <?php endif; ?>
                
                <a href="/logout" class="menu-item menu-item-logout">Logout</a>
            </nav>
        </aside>
        <main class="main-content">
