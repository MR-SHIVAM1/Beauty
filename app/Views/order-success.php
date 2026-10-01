<div class="container">
    <div class="success-wrap">
        <div class="success-check"><?= icon_check() ?></div>
        <h1>Order Placed Successfully!</h1>
        <p style="color: var(--ink-soft);">Thank you, <strong><?= e($order['address_name']) ?></strong>! Your order has been confirmed and is being prepared.</p>
        <div class="order-number-pill">Order #<?= e($order['order_number']) ?></div>

        <div class="panel" style="text-align:left;">
            <span class="info-label">Items</span>
            <?php foreach ($items as $item): ?>
                <div class="cart-item" style="border:none; padding: 10px 0;">
                    <img src="<?= asset('images/products/' . $item['product_image']) ?>" alt="<?= e($item['product_name']) ?>">
                    <div class="cart-item-info"><h4><?= e($item['product_name']) ?></h4><span style="font-size:13px; color:var(--ink-soft);">Qty <?= $item['quantity'] ?></span></div>
                    <div style="font-weight:800;"><?= money($item['price'] * $item['quantity']) ?></div>
                </div>
            <?php endforeach; ?>
            <div class="summary-total"><span>Total Paid</span><span><?= money($order['total']) ?></span></div>
        </div>

        <div class="btn-group" style="justify-content:center;">
            <a href="/orders/<?= e($order['order_number']) ?>" class="btn btn-primary">Track This Order</a>
            <a href="/" class="btn btn-outline">Continue Shopping</a>
        </div>
    </div>
</div>
