<div class="auth-wrap">
    <div class="auth-visual">
        <div class="logo" style="color:#fff;"><span class="logo-badge">🍯</span>Honey<span style="color: var(--gold-light);">Beauty</span></div>
        <h2>Welcome back to your beauty destination.</h2>
        <p>Sign in to track orders, manage your wishlist, and check out faster with saved details.</p>
        <div class="auth-badges">
            <span>✨ 40+ Premium Products</span>
            <span>🚚 Free Delivery</span>
            <span>🔒 Secure Checkout</span>
        </div>
    </div>
    <div class="auth-form-side">
        <div class="auth-card">
            <div class="logo"><span class="logo-badge">🍯</span>Honey<span>Beauty</span></div>
            <h1>Sign In</h1>
            <p class="sub">Welcome back! Please enter your details.</p>

            <?php if ($msg = flash('error')): ?><div class="alert alert-error"><?= e($msg) ?></div><?php endif; ?>
            <?php if ($msg = flash('success')): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>

            <form action="/login" method="post">
                <?php csrf(); ?>
                <div class="field"><label>Email or Username</label><input type="text" name="email" placeholder="you@example.com" required autofocus></div>
                <div class="field"><label>Password</label><input type="password" name="password" placeholder="Enter your password" required></div>
                <button type="submit" class="btn btn-primary btn-block" style="padding:15px; margin-top:8px;">Sign In &amp; Shop Now</button>
            </form>

            <div class="admin-hint">Store admin? Sign in with <strong>admin</strong> / <strong>admin</strong> to reach the admin panel.</div>
            <p class="auth-switch">New to Honey Beauty? <a href="/signup">Create an account</a></p>
        </div>
    </div>
</div>
