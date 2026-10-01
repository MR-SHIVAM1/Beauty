<div class="container">
    <div class="section-title"><h2>Your Wishlist</h2><p><?= count($items) ?> item<?= count($items) === 1 ? '' : 's' ?></p></div>

    <?php if (empty($items)): ?>
        <div class="empty-state">
            <?= icon_heart() ?>
            <h3>Your wishlist is empty</h3>
            <p>Tap the heart on any product to save it for later.</p>
            <a href="/" class="btn btn-primary" style="margin-top: 20px;">Discover Products</a>
        </div>
    <?php else: ?>
        <div class="products-grid" style="margin-bottom: 60px;">
            <?php foreach ($items as $p): $off = $p['mrp'] > $p['price'] ? round((1 - $p['price'] / $p['mrp']) * 100) : 0; ?>
                <div class="product-card">
                    <a href="/product/<?= e($p['slug']) ?>" class="product-thumb">
                        <img src="<?= asset('images/products/' . $p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
                    </a>
                    <?php if ($off > 0): ?><span class="discount-pill"><?= $off ?>% OFF</span><?php endif; ?>
                    <form class="js-wishlist-form" action="/wishlist/toggle" method="post" style="position:absolute; inset:0; pointer-events:none;">
                        <?php csrf(); ?>
                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="redirect" value="/wishlist">
                        <button type="submit" class="wishlist-btn active" style="pointer-events:auto;"><?= icon_heart() ?></button>
                    </form>
                    <a href="/product/<?= e($p['slug']) ?>" class="product-info">
                        <span class="product-cat"><?= category_label($p['category']) ?></span>
                        <span class="product-name"><?= e($p['name']) ?></span>
                        <div class="price-row"><span class="price-now"><?= money($p['price']) ?></span></div>
                    </a>
                    <form class="js-cart-form" action="/cart/add" method="post" style="padding: 0 16px 16px;">
                        <?php csrf(); ?>
                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="redirect" value="/wishlist">
                        <button type="submit" class="add-cart-mini"><?= icon_cart() ?> Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
