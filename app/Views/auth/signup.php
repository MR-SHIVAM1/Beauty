<div class="auth-wrap">
    <div class="auth-visual">
        <div class="logo" style="color:#fff;"><span class="logo-badge">🍯</span>Honey<span style="color: var(--gold-light);">Beauty</span></div>
        <h2>Join thousands glowing with Honey Beauty.</h2>
        <p>Create your free account to save favorites, track orders in real time, and unlock members-only promo codes.</p>
        <div class="auth-badges">
            <span>💄 Curated Collections</span>
            <span>🎁 Exclusive Promo Codes</span>
            <span>📦 Live Order Tracking</span>
        </div>
    </div>
    <div class="auth-form-side">
        <div class="auth-card">
            <div class="logo"><span class="logo-badge">🍯</span>Honey<span>Beauty</span></div>
            <h1>Create Account</h1>
            <p class="sub">Let's get you set up in seconds.</p>

            <?php if ($msg = flash('error')): ?><div class="alert alert-error"><?= e($msg) ?></div><?php endif; ?>

            <form action="/signup" method="post">
                <?php csrf(); ?>
                <div class="field"><label>Full Name</label><input type="text" name="name" value="<?= old('name') ?>" placeholder="Your full name" required autofocus></div>
                <div class="field"><label>Email Address</label><input type="email" name="email" value="<?= old('email') ?>" placeholder="you@example.com" required></div>
                <div class="field"><label>Mobile Number</label><input type="tel" name="phone" value="<?= old('phone') ?>" placeholder="10-digit mobile number" required></div>
                <div class="field-row">
                    <div class="field"><label>Password</label><input type="password" name="password" placeholder="8+ characters" required minlength="8"></div>
                    <div class="field"><label>Confirm Password</label><input type="password" name="password_confirmation" placeholder="Re-enter password" required minlength="8"></div>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="padding:15px; margin-top:8px;">Create Account</button>
            </form>

            <p class="auth-switch">Already have an account? <a href="/login">Sign in</a></p>
        </div>
    </div>
</div>
