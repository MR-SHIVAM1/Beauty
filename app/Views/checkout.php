<div class="container">
    <div class="section-title"><h2>Checkout</h2><p>Secure demo checkout — no real payment is processed</p></div>

    <div class="checkout-layout">
        <div>
            <form action="/checkout/place" method="post" id="checkoutForm">
                <?php csrf(); ?>
                <input type="hidden" name="payment_method" id="paymentMethodInput" value="card">

                <div class="panel">
                    <div class="checkout-step-title"><span class="num">1</span> Delivery Address</div>
                    <div class="field"><label>Full Name</label><input type="text" name="c_name" value="<?= e($user['name']) ?>" required></div>
                    <div class="field-row">
                        <div class="field"><label>Mobile Number</label><input type="tel" name="c_phone" value="<?= e($user['phone'] ?? '') ?>" placeholder="10-digit mobile number" required></div>
                        <div class="field"><label>Pincode</label><input type="text" name="c_pincode" placeholder="6-digit pincode" required></div>
                    </div>
                    <div class="field"><label>Flat / House No. / Street / Landmark</label><input type="text" name="c_address" placeholder="e.g. 12B Rose Apartments, MG Road" required></div>
                </div>

                <div class="panel">
                    <div class="checkout-step-title"><span class="num">2</span> Payment Method</div>
                    <div class="pay-methods">
                        <div class="pay-method active" data-method="card"><?= icon_card() ?><span>Card</span></div>
                        <div class="pay-method" data-method="upi"><?= icon_upi() ?><span>UPI</span></div>
                        <div class="pay-method" data-method="cod"><?= icon_cash() ?><span>Cash on Delivery</span></div>
                    </div>

                    <div class="pay-panel active" id="pay-card">
                        <div class="demo-card-preview">
                            <span class="demo-badge">DEMO CARD</span>
                            <div class="demo-card-top"><div class="demo-chip"></div></div>
                            <div class="demo-card-number" id="previewCardNumber">•••• •••• •••• ••••</div>
                            <div class="demo-card-bottom">
                                <span id="previewCardName">YOUR NAME</span>
                                <span id="previewCardExpiry">MM/YY</span>
                            </div>
                        </div>
                        <div class="field"><label>Card Number</label><input type="text" id="card_number" name="card_number" placeholder="4111 1111 1111 1111" maxlength="19" autocomplete="off"></div>
                        <div class="field-row">
                            <div class="field"><label>Expiry (MM/YY)</label><input type="text" id="card_expiry" name="card_expiry" placeholder="12/28" maxlength="5" autocomplete="off"></div>
                            <div class="field"><label>CVV</label><input type="text" name="card_cvv" placeholder="123" maxlength="4" autocomplete="off"></div>
                        </div>
                        <div class="field"><label>Name on Card</label><input type="text" id="card_name" name="card_name" placeholder="As printed on card" autocomplete="off"></div>
                        <p style="font-size:12.5px; color: var(--ink-faint);">This is a simulated payment form for demo purposes. No real card data is processed or stored.</p>
                    </div>

                    <div class="pay-panel" id="pay-upi">
                        <div class="upi-box">
                            <div class="qr-fake"></div>
                            <p style="font-weight:700; margin-bottom: 4px;">Scan &amp; Pay (Demo)</p>
                            <p style="font-size:12.5px; color: var(--ink-soft);">Or enter your UPI ID below to simulate payment</p>
                        </div>
                        <div class="field"><label>UPI ID</label><input type="text" name="upi_id" placeholder="yourname@bank"></div>
                    </div>

                    <div class="pay-panel" id="pay-cod">
                        <div class="upi-box" style="border-style: solid;">
                            <?= icon_cash() ?>
                            <p style="font-weight:700; margin: 10px 0 4px;">Pay with cash when your order arrives</p>
                            <p style="font-size:12.5px; color: var(--ink-soft);">Please keep exact change ready for our delivery partner.</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding:16px;">Place Order — <?= money($total) ?></button>
            </form>
        </div>

        <div class="summary-card">
            <h3>Order Summary</h3>
            <?php foreach ($items as $item): ?>
                <div class="summary-row"><span><?= e($item['name']) ?> × <?= $item['quantity'] ?></span><strong><?= money($item['price'] * $item['quantity']) ?></strong></div>
            <?php endforeach; ?>

            <hr style="border:none; border-top:1px solid var(--border); margin: 14px 0;">

            <?php if ($promo): ?>
                <div class="promo-applied">
                    <span><?= icon_check() ?> <?= e($promo['code']) ?> applied (<?= $promo['discount_percent'] ?>%)</span>
                    <form action="/checkout/promo/remove" method="post" style="display:inline;">
                        <?php csrf(); ?>
                        <button type="submit">Remove</button>
                    </form>
                </div>
            <?php else: ?>
                <form action="/checkout/promo" method="post" class="promo-row">
                    <?php csrf(); ?>
                    <input type="text" name="promo_code" placeholder="Promo code (try WELCOME10)">
                    <button type="submit" class="btn btn-outline btn-sm">Apply</button>
                </form>
            <?php endif; ?>

            <div class="summary-row" style="margin-top: 14px;"><span>Subtotal</span><strong><?= money($subtotal) ?></strong></div>
            <?php if ($discount > 0): ?><div class="summary-row discount-row"><span>Discount</span><strong>−<?= money($discount) ?></strong></div><?php endif; ?>
            <div class="summary-row"><span>Delivery</span><strong style="color: var(--success);">Free</strong></div>
            <div class="summary-total"><span>Total</span><span><?= money($total) ?></span></div>
        </div>
    </div>
</div>
