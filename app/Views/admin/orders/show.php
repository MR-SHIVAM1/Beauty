<?php use App\Core\View; ?>
<div class="admin-topbar">
    <h1>Order #<?= e($order['order_number']) ?></h1>
    <a href="/admin/orders" class="back-store-link">← Back to Orders</a>
</div>

<div class="admin-panel">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; margin-bottom:16px;">
        <span style="color:var(--ink-soft); font-size:13.5px;">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?> by <?= e($order['address_name']) ?></span>
        <form action="/admin/orders/<?= $order['id'] ?>/status" method="post" style="display:flex; gap:10px; align-items:center;">
            <?php csrf(); ?>
            <select name="status" class="status-select">
                <?php foreach (['awaiting' => 'Awaiting Confirmation', 'processing' => 'Processing', 'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $order['status'] === $key ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-dark btn-sm">Update Status</button>
        </form>
    </div>
    <?php View::partial('partials/tracker', ['status' => $order['status']]); ?>
</div>

<div class="admin-form-grid" style="grid-template-columns: 2fr 1fr;">
    <div class="admin-panel">
        <h3>Items</h3>
        <?php foreach ($items as $item): ?>
            <div class="cart-item" style="border:none; border-bottom:1px solid var(--border); border-radius:0; padding:14px 0;">
                <img src="<?= asset('images/products/' . $item['product_image']) ?>" alt="<?= e($item['product_name']) ?>">
                <div class="cart-item-info"><h4><?= e($item['product_name']) ?></h4><span style="font-size:13px; color:var(--ink-soft);">Qty <?= $item['quantity'] ?> × <?= money($item['price']) ?></span></div>
                <div style="font-weight:800;"><?= money($item['price'] * $item['quantity']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div>
        <div class="admin-panel">
            <h3>Delivery Address</h3>
            <p class="info-text"><strong><?= e($order['address_name']) ?></strong><br><?= e($order['address_phone']) ?><br><?= e($order['address_line']) ?><br>PIN: <?= e($order['address_pincode']) ?></p>
        </div>
        <div class="admin-panel">
            <h3>Payment</h3>
            <div class="summary-row"><span>Method</span><strong style="text-transform:uppercase;"><?= e($order['payment_method']) ?></strong></div>
            <div class="summary-row"><span>Subtotal</span><strong><?= money($order['subtotal']) ?></strong></div>
            <?php if ($order['discount'] > 0): ?><div class="summary-row discount-row"><span>Discount</span><strong>−<?= money($order['discount']) ?></strong></div><?php endif; ?>
            <div class="summary-total"><span>Total</span><span><?= money($order['total']) ?></span></div>
        </div>
    </div>
</div>
