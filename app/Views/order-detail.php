<?php use App\Core\View; ?>
<div class="container">
    <div class="crumbs"><a href="/orders">My Orders</a> / Order #<?= e($order['order_number']) ?></div>

    <div class="panel">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom: 10px;">
            <div>
                <h2 style="font-size:22px;">Order #<?= e($order['order_number']) ?></h2>
                <span style="font-size:13px; color:var(--ink-soft);">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></span>
            </div>
            <span class="status-badge status-<?= e($order['status']) ?>"><?= status_label($order['status']) ?></span>
        </div>

        <?php View::partial('partials/tracker', ['status' => $order['status']]); ?>
    </div>

    <div class="details-layout" style="grid-template-columns: 1fr 340px;">
        <div class="panel">
            <span class="info-label">Items</span>
            <?php foreach ($items as $item): ?>
                <div class="cart-item" style="border:none; border-bottom: 1px solid var(--border); border-radius:0; padding: 14px 0;">
                    <img src="<?= asset('images/products/' . $item['product_image']) ?>" alt="<?= e($item['product_name']) ?>">
                    <div class="cart-item-info"><h4><?= e($item['product_name']) ?></h4><span style="font-size:13px; color:var(--ink-soft);">Qty <?= $item['quantity'] ?> × <?= money($item['price']) ?></span></div>
                    <div style="font-weight:800;"><?= money($item['price'] * $item['quantity']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div>
            <div class="panel">
                <span class="info-label">Delivery Address</span>
                <p class="info-text"><strong><?= e($order['address_name']) ?></strong><br><?= e($order['address_phone']) ?><br><?= e($order['address_line']) ?><br>PIN: <?= e($order['address_pincode']) ?></p>
            </div>
            <div class="panel">
                <span class="info-label">Payment Summary</span>
                <div class="summary-row"><span>Payment Method</span><strong style="text-transform:uppercase;"><?= e($order['payment_method']) ?></strong></div>
                <div class="summary-row"><span>Subtotal</span><strong><?= money($order['subtotal']) ?></strong></div>
                <?php if ($order['discount'] > 0): ?><div class="summary-row discount-row"><span>Discount <?= $order['promo_code'] ? '(' . e($order['promo_code']) . ')' : '' ?></span><strong>−<?= money($order['discount']) ?></strong></div><?php endif; ?>
                <div class="summary-total"><span>Total</span><span><?= money($order['total']) ?></span></div>
            </div>
        </div>
    </div>
</div>
