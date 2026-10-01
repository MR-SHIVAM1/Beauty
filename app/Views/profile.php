<div class="container">
    <div class="profile-layout">
        <div class="profile-card">
            <div class="profile-avatar"><?= e(strtoupper(substr($user['name'], 0, 1))) ?></div>
            <h3><?= e($user['name']) ?></h3>
            <p style="font-size:13px; color:var(--ink-soft); margin-top:4px;"><?= e($user['email']) ?></p>
            <?php if ($user['role'] === 'admin'): ?><span class="status-badge status-processing" style="margin-top:10px; display:inline-block;">Admin Account</span><?php endif; ?>
            <div class="profile-stats">
                <div><strong><?= $orderCount ?></strong><span>Orders</span></div>
                <div><strong><?= $wishlistCount ?></strong><span>Wishlist</span></div>
            </div>
            <div class="btn-group" style="margin-top:20px; flex-direction:column;">
                <a href="/orders" class="btn btn-outline btn-block btn-sm">My Orders</a>
                <a href="/wishlist" class="btn btn-outline btn-block btn-sm">My Wishlist</a>
            </div>
        </div>

        <div class="panel">
            <span class="info-label">Edit Profile</span>
            <form action="/profile/update" method="post">
                <?php csrf(); ?>
                <div class="field"><label>Full Name</label><input type="text" name="name" value="<?= e($user['name']) ?>" required></div>
                <div class="field"><label>Email Address</label><input type="email" value="<?= e($user['email']) ?>" disabled style="background:var(--cream); color:var(--ink-faint);"></div>
                <div class="field"><label>Mobile Number</label><input type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>" placeholder="Add your mobile number"></div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</div>
