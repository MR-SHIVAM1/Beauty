<div class="admin-topbar">
    <h1>Promo Codes</h1>
</div>

<div class="admin-form-grid" style="grid-template-columns: 2fr 1fr;">
    <div class="admin-panel">
        <h3>All Promo Codes</h3>
        <?php if (empty($promos)): ?>
            <p class="empty-admin">No promo codes yet.</p>
        <?php else: ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead><tr><th>Code</th><th>Discount</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($promos as $p): ?>
                        <tr>
                            <td><strong><?= e($p['code']) ?></strong></td>
                            <td><?= (int) $p['discount_percent'] ?>% off</td>
                            <td>
                                <form action="/admin/promos/<?= $p['id'] ?>/toggle" method="post">
                                    <?php csrf(); ?>
                                    <button type="submit" class="toggle-pill <?= $p['active'] ? 'on' : 'off' ?>"><?= $p['active'] ? 'Active' : 'Disabled' ?></button>
                                </form>
                            </td>
                            <td>
                                <form action="/admin/promos/<?= $p['id'] ?>/delete" method="post" onsubmit="return confirm('Delete this promo code?');">
                                    <?php csrf(); ?>
                                    <button type="submit" class="icon-action danger" title="Delete"><?= icon_trash() ?></button>
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
        <h3>Create Promo Code</h3>
        <form action="/admin/promos" method="post">
            <?php csrf(); ?>
            <div class="field"><label>Code</label><input type="text" name="code" placeholder="e.g. SUMMER25" required style="text-transform:uppercase;"></div>
            <div class="field"><label>Discount Percentage</label><input type="number" name="discount_percent" min="1" max="90" placeholder="10" required></div>
            <button type="submit" class="btn btn-primary btn-block">Create Code</button>
        </form>
    </div>
</div>
