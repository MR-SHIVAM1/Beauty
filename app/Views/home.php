<?php $user = current_user(); ?>

<div class="container">
    <section class="hero">
        <div class="hero-text">
            <span class="hero-eyebrow">✨ New Season Collection</span>
            <h1>Beauty that feels<br>as good as it looks.</h1>
            <p>Discover premium, cruelty-free makeup and skincare — carefully formulated, honestly priced, and made to give you that effortless glow.</p>
            <div class="btn-group">
                <a href="#shop" class="btn btn-primary">Shop Now</a>
                <a href="/?category=skincare" class="btn btn-outline">Explore Skincare</a>
            </div>
            <div class="hero-stats">
                <div><strong>40+</strong><span>Curated Products</span></div>
                <div><strong>4.7★</strong><span>Average Rating</span></div>
                <div><strong>Free</strong><span>Delivery Over ₹499</span></div>
            </div>
        </div>
    </section>

    <div id="shop" class="section-title">
        <h2><?= $category === 'makeup' ? '💄 Makeup Collection' : ($category === 'skincare' ? '🌿 Skincare Routine' : ($category === 'tools' ? '🖌️ Tools & Brushes' : 'Our Best Sellers')) ?></h2>
        <p><?= count($products) ?> products<?= $search ? ' matching "' . e($search) . '"' : '' ?></p>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <?= icon_bag() ?>
            <h3>No products found</h3>
            <p>Try a different search term or browse another category.</p>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($products as $i => $p): $off = $p['mrp'] > $p['price'] ? round((1 - $p['price'] / $p['mrp']) * 100) : 0; ?>
                <div class="product-card" style="animation-delay: <?= min($i, 12) * 0.04 ?>s">
                    <a href="/product/<?= e($p['slug']) ?>" class="product-thumb">
                        <img src="<?= asset('images/products/' . $p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
                    </a>
                    <?php if ($off > 0): ?><span class="discount-pill"><?= $off ?>% OFF</span><?php endif; ?>

                    <?php if ($user): ?>
                        <form class="js-wishlist-form" action="/wishlist/toggle" method="post" style="position:absolute; inset:0; pointer-events:none;">
                            <?php csrf(); ?>
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="redirect" value="/?category=<?= e($category) ?>">
                            <button type="submit" class="wishlist-btn <?= in_array($p['id'], $wishlistIds) ? 'active' : '' ?>" style="pointer-events:auto;">
                                <?= icon_heart() ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="/login" class="wishlist-btn"><?= icon_heart() ?></a>
                    <?php endif; ?>

                    <a href="/product/<?= e($p['slug']) ?>" class="product-info">
                        <span class="product-cat"><?= category_label($p['category']) ?></span>
                        <span class="product-name"><?= e($p['name']) ?></span>
                        <div class="rating-row"><span class="stars"><?= star_rating((float) $p['rating']) ?></span> <?= number_format((float) $p['rating'], 1) ?></div>
                        <div class="price-row">
                            <span class="price-now"><?= money($p['price']) ?></span>
                            <?php if ($off > 0): ?><span class="price-mrp"><?= money($p['mrp']) ?></span><span class="price-off"><?= $off ?>% off</span><?php endif; ?>
                        </div>
                    </a>

                    <?php if ($user): ?>
                        <form class="js-cart-form" action="/cart/add" method="post" style="padding: 0 16px 16px;">
                            <?php csrf(); ?>
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="redirect" value="/?category=<?= e($category) ?>">
                            <button type="submit" class="add-cart-mini"><?= icon_cart() ?> Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <div style="padding: 0 16px 16px;">
                            <a href="/login" class="add-cart-mini" style="text-decoration:none;"><?= icon_cart() ?> Add to Cart</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
