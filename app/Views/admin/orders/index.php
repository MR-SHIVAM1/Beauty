<div class="admin-topbar">
    <h1>Orders</h1>
</div>

<div class="admin-panel">
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px;">
        <?php foreach (['all' => 'All', 'awaiting' => 'Awaiting', 'processing' => 'Processing', 'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $key => $label): ?>
            <a href="/admin/orders?status=<?= $key ?>" class="tab-pill <?= $status === $key ? 'active' : '' ?>" style="padding:8px 16px; font-size:13px;"><?= $label ?></a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($orders)): ?>
        <p class="empty-admin">No orders found for this filter.</p>
    <?php else: ?>
        <div class="table-scroll">
            <table class="data-table">
                <thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td>#<?= e($o['order_number']) ?></td>
                        <td><?= e($o['customer_name']) ?><br><span style="color:var(--ink-faint); font-size:12px;"><?= e($o['customer_email']) ?></span></td>
                        <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                        <td><?= money($o['total']) ?></td>
                        <td><span class="status-badge status-<?= e($o['status']) ?>"><?= status_label($o['status']) ?></span></td>
                        <td><a href="/admin/orders/<?= $o['id'] ?>" class="icon-action" title="View"><?= icon_edit() ?></a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
