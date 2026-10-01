<div class="container">
    <div class="section-title"><h2>My Orders</h2><p><?= count($orders) ?> order<?= count($orders) === 1 ? '' : 's' ?></p></div>

    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <?= icon_box() ?>
            <h3>No orders yet</h3>
            <p>When you place an order, it will show up here.</p>
            <a href="/" class="btn btn-primary" style="margin-top: 20px;">Start Shopping</a>
        </div>
    <?php else: ?>
        <div style="padding-bottom: 60px;">
            <?php foreach ($orders as $order): ?>
                <a href="/orders/<?= e($order['order_number']) ?>" class="order-row">
                    <div>
                        <div style="font-weight:800; margin-bottom:4px;">Order #<?= e($order['order_number']) ?></div>
                        <div style="font-size:13px; color: var(--ink-soft);"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?> · <?= money($order['total']) ?></div>
                    </div>
                    <span class="status-badge status-<?= e($order['status']) ?>"><?= status_label($order['status']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
