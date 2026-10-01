<div class="container">
    <div class="section-title"><h2>Your Cart</h2><p><?= count($items) ?> item<?= count($items) === 1 ? '' : 's' ?></p></div>

    <?php if (empty($items)): ?>
        <div class="empty-state">
            <?= icon_cart() ?>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added anything yet.</p>
            <a href="/" class="btn btn-primary" style="margin-top: 20px;">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            <div class="cart-list">
                <?php foreach ($items as $item): ?>
                    <div class="cart-item">
                        <a href="/product/<?= e($item['slug']) ?>"><img src="<?= asset('images/products/' . $item['image']) ?>" alt="<?= e($item['name']) ?>"></a>
                        <div class="cart-item-info">
                            <h4><a href="/product/<?= e($item['slug']) ?>"><?= e($item['name']) ?></a></h4>
                            <div class="price-row"><span class="price-now" style="font-size:16px;"><?= money($item['price']) ?></span></div>
                            <div class="cart-item-actions">
                                <form action="/cart/update" method="post">
                                    <?php csrf(); ?>
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <select name="quantity" class="cart-qty-select" style="padding:8px 10px; border-radius:8px; border:1.5px solid var(--border);">
                                        <?php for ($q = 1; $q <= 10; $q++): ?>
                                            <option value="<?= $q ?>" <?= $q == $item['quantity'] ? 'selected' : '' ?>><?= $q ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </form>
                                <form action="/cart/remove" method="post">
                                    <?php csrf(); ?>
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="remove-link"><?= icon_trash() ?> Remove</button>
                                </form>
                            </div>
                        </div>
                        <div style="font-weight:800; font-size:16px;"><?= money($item['price'] * $item['quantity']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary-card">
                <h3>Order Summary</h3>
                <div class="summary-row"><span>Subtotal</span><strong><?= money($subtotal) ?></strong></div>
                <div class="summary-row"><span>Delivery</span><strong style="color: var(--success);">Free</strong></div>
                <div class="summary-total"><span>Total</span><span><?= money($subtotal) ?></span></div>
                <a href="/checkout" class="btn btn-primary btn-block">Proceed to Checkout</a>
                <a href="/" class="btn btn-ghost btn-block" style="margin-top:8px;">Continue Shopping</a>
            </div>
        </div>
    <?php endif; ?>
</div>
