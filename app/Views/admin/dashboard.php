<div class="admin-topbar">
    <h1>Dashboard</h1>
    <a href="/" class="back-store-link"><?= icon_grid() ?> View Store</a>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-icon gold"><?= icon_rupee() ?></div><div><strong><?= money($revenue) ?></strong><span>Total Revenue</span></div></div>
    <div class="stat-card"><div class="stat-icon rose"><?= icon_truck() ?></div><div><strong><?= $orderCount ?></strong><span>Total Orders</span></div></div>
    <div class="stat-card"><div class="stat-icon blue"><?= icon_box() ?></div><div><strong><?= $productCount ?></strong><span>Products</span></div></div>
    <div class="stat-card"><div class="stat-icon green"><?= icon_users() ?></div><div><strong><?= $userCount ?></strong><span>Registered Users</span></div></div>
</div>

<div class="admin-form-grid" style="grid-template-columns: 2fr 1fr;">
    <div class="admin-panel">
        <h3>Recent Orders</h3>
        <?php if (empty($recentOrders)): ?>
            <p class="empty-admin">No orders placed yet.</p>
        <?php else: ?>
            <div class="table-scroll">
                <table class="data-table">
                    <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php foreach ($recentOrders as $o): ?>
                        <tr onclick="window.location='/admin/orders/<?= $o['id'] ?>'" style="cursor:pointer;">
                            <td>#<?= e($o['order_number']) ?></td>
                            <td><?= e($o['customer_name']) ?></td>
                            <td><?= money($o['total']) ?></td>
                            <td><span class="status-badge status-<?= e($o['status']) ?>"><?= status_label($o['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="admin-panel">
        <h3>Orders by Status</h3>
        <?php foreach ($statusCounts as $key => $count): ?>
            <div class="summary-row"><span class="status-badge status-<?= $key ?>"><?= status_label($key) ?></span><strong><?= $count ?></strong></div>
        <?php endforeach; ?>
    </div>
</div>
