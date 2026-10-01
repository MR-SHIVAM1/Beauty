<?php
$user = current_user();
$cartCount = is_logged_in() ? \App\Models\Cart::count($user['id']) : 0;
$wishlistCount = is_logged_in() ? count(\App\Models\Wishlist::items($user['id'])) : 0;
$activeCategory = $category ?? null;
?>
<header class="site-header">
    <div class="container header-inner">
        <a href="/" class="logo"><span class="logo-badge">🍯</span>Honey<span>Beauty</span></a>

        <form class="nav-search" action="/" method="get">
            <?= icon_search() ?>
            <input type="text" name="q" placeholder="Search lipstick, serum, sunscreen..." value="<?= e($search ?? '') ?>">
        </form>

        <button class="mobile-toggle" id="mobileToggle" aria-label="Menu"><?= icon_menu() ?></button>

        <nav class="nav-links">
            <a href="/wishlist" class="nav-icon-link" title="Wishlist">
                <?= icon_heart() ?>
                <span class="badge-count wishlist-count" style="<?= $wishlistCount ? '' : 'display:none' ?>"><?= $wishlistCount ?></span>
            </a>
            <a href="/cart" class="nav-icon-link" title="Cart">
                <?= icon_cart() ?>
                <span class="badge-count cart-count" style="<?= $cartCount ? '' : 'display:none' ?>"><?= $cartCount ?></span>
            </a>

            <?php if ($user): ?>
                <div class="user-menu">
                    <button type="button" class="user-chip">
                        <span class="user-avatar"><?= e(strtoupper(substr($user['name'], 0, 1))) ?></span>
                        <span class="chip-name" style="font-weight:700; font-size:13.5px;"><?= e(explode(' ', $user['name'])[0]) ?></span>
                    </button>
                    <div class="user-dropdown">
                        <?php if ($user['role'] === 'admin'): ?>
                            <a href="/admin"><?= icon_gauge() ?> Admin Dashboard</a>
                            <hr>
                        <?php endif; ?>
                        <a href="/profile"><?= icon_user() ?> My Profile</a>
                        <a href="/orders"><?= icon_box() ?> My Orders</a>
                        <a href="/wishlist"><?= icon_heart() ?> Wishlist</a>
                        <hr>
                        <form action="/logout" method="post">
                            <?php csrf(); ?>
                            <button type="submit"><?= icon_logout() ?> Sign Out</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="/login" class="btn btn-outline btn-sm">Sign In</a>
                <a href="/signup" class="btn btn-primary btn-sm">Sign Up</a>
            <?php endif; ?>
        </nav>
    </div>

    <div id="mobileMenu" class="mobile-menu">
        <div class="container" style="padding: 0 24px 20px; display:flex; flex-direction:column; gap:10px;">
            <form action="/" method="get">
                <input type="text" name="q" placeholder="Search products..." value="<?= e($search ?? '') ?>"
                    style="width:100%; padding:12px 16px; border-radius: var(--radius-pill); border:1.5px solid var(--border);">
            </form>
            <?php if ($user): ?>
                <a href="/profile" class="btn btn-outline btn-sm btn-block">My Profile</a>
                <a href="/orders" class="btn btn-outline btn-sm btn-block">My Orders</a>
                <?php if ($user['role'] === 'admin'): ?><a href="/admin" class="btn btn-outline btn-sm btn-block">Admin Dashboard</a><?php endif; ?>
                <form action="/logout" method="post">
                    <?php csrf(); ?>
                    <button type="submit" class="btn btn-dark btn-sm btn-block">Sign Out</button>
                </form>
            <?php else: ?>
                <a href="/login" class="btn btn-outline btn-sm btn-block">Sign In</a>
                <a href="/signup" class="btn btn-primary btn-sm btn-block">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="category-strip">
        <div class="container">
            <a href="/" class="tab-pill <?= ($activeCategory === 'all' || $activeCategory === null) ? 'active' : '' ?>">✨ All Products</a>
            <a href="/?category=makeup" class="tab-pill <?= $activeCategory === 'makeup' ? 'active' : '' ?>">💄 Makeup</a>
            <a href="/?category=skincare" class="tab-pill <?= $activeCategory === 'skincare' ? 'active' : '' ?>">🌿 Skincare</a>
            <a href="/?category=tools" class="tab-pill <?= $activeCategory === 'tools' ? 'active' : '' ?>">🖌️ Tools & Brushes</a>
        </div>
    </div>
</header>

<div class="toast-stack" id="toastStack">
    <?php if ($msg = flash('success')): ?><div class="toast success"><?= icon_check() ?><p><?= e($msg) ?></p></div><?php endif; ?>
    <?php if ($msg = flash('error')): ?><div class="toast error"><?= icon_check() ?><p><?= e($msg) ?></p></div><?php endif; ?>
</div>
