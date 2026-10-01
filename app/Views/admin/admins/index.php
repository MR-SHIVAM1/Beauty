<div class="admin-topbar">
    <h1>Manage Admins</h1>
</div>

<div class="admin-form-grid" style="grid-template-columns: 2fr 1fr;">
    <div class="admin-panel">
        <h3>Current Admins</h3>
        <div class="table-scroll">
            <table class="data-table">
                <thead><tr><th>Name</th><th>Email</th><th>Since</th></tr></thead>
                <tbody>
                <?php foreach ($admins as $a): ?>
                    <tr>
                        <td><strong><?= e($a['name']) ?></strong></td>
                        <td><?= e($a['email']) ?></td>
                        <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h3 style="margin-top:28px;">Customers</h3>
        <?php if (empty($customers)): ?>
            <p class="empty-admin">No customer accounts yet.</p>
        <?php else: ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead><tr><th>Name</th><th>Email</th><th>Joined</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td><?= e($c['name']) ?></td>
                            <td><?= e($c['email']) ?></td>
                            <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                            <td>
                                <form action="/admin/admins" method="post" onsubmit="return confirm('Promote <?= e(addslashes($c['name'])) ?> to admin?');">
                                    <?php csrf(); ?>
                                    <input type="hidden" name="mode" value="promote">
                                    <input type="hidden" name="email" value="<?= e($c['email']) ?>">
                                    <button type="submit" class="btn btn-outline btn-sm">Promote to Admin</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="admin-panel">
        <h3>Add New Admin</h3>
        <form action="/admin/admins" method="post">
            <?php csrf(); ?>
            <input type="hidden" name="mode" value="create">
            <div class="field"><label>Full Name</label><input type="text" name="name" required></div>
            <div class="field"><label>Email Address</label><input type="email" name="email" required></div>
            <div class="field"><label>Mobile Number</label><input type="tel" name="phone"></div>
            <div class="field"><label>Password</label><input type="password" name="password" minlength="8" required></div>
            <button type="submit" class="btn btn-primary btn-block">Create Admin</button>
        </form>
    </div>
</div>
