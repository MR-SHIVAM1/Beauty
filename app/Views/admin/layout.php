<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin | Honey Beauty') ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍯</text></svg>">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<?php $path = \App\Core\Request::path(); ?>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="logo"><span class="logo-badge">🍯</span>Honey<span>Beauty</span></div>
        <nav class="admin-nav">
            <a href="/admin" class="<?= $path === '/admin' ? 'active' : '' ?>"><?= icon_gauge() ?> Dashboard</a>
            <a href="/admin/products" class="<?= str_starts_with($path, '/admin/products') ? 'active' : '' ?>"><?= icon_box() ?> Products</a>
            <a href="/admin/orders" class="<?= str_starts_with($path, '/admin/orders') ? 'active' : '' ?>"><?= icon_truck() ?> Orders</a>
            <a href="/admin/promos" class="<?= str_starts_with($path, '/admin/promos') ? 'active' : '' ?>"><?= icon_tag() ?> Promo Codes</a>
            <a href="/admin/admins" class="<?= str_starts_with($path, '/admin/admins') ? 'active' : '' ?>"><?= icon_users() ?> Admins</a>
            <div class="divider"></div>
            <a href="/"><?= icon_grid() ?> Back to Store</a>
            <form action="/logout" method="post">
                <?php echo \App\Core\Csrf::field(); ?>
                <button type="submit" style="all:unset; display:flex; align-items:center; gap:12px; padding:12px 14px; border-radius: var(--radius-sm); font-size:14px; font-weight:600; color: rgba(255,255,255,0.7); width:100%; cursor:pointer;"><?= icon_logout() ?> Sign Out</button>
            </form>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="toast-stack" id="toastStack">
            <?php if ($msg = flash('success')): ?><div class="toast success"><?= icon_check() ?><p><?= e($msg) ?></p></div><?php endif; ?>
            <?php if ($msg = flash('error')): ?><div class="toast error"><?= icon_check() ?><p><?= e($msg) ?></p></div><?php endif; ?>
        </div>
        <?= $content ?>
    </main>
</div>
<script src="/assets/js/app.js"></script>
</body>
</html>
