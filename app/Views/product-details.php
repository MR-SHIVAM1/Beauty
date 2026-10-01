<?php $user = current_user(); $off = $product['mrp'] > $product['price'] ? round((1 - $product['price'] / $product['mrp']) * 100) : 0; ?>

<div class="container">
    <div class="crumbs">
        <a href="/">Home</a> / <a href="/?category=<?= e($product['category']) ?>"><?= category_label($product['category']) ?></a> / <?= e($product['name']) ?>
    </div>

    <div class="details-layout">
        <div class="gallery">
            <div class="gallery-main">
                <img src="<?= asset('images/products/' . $product['image']) ?>" alt="<?= e($product['name']) ?>">
            </div>
        </div>

        <div class="details-right">
            <div class="panel">
                <span class="product-cat"><?= category_label($product['category']) ?></span>
                <h1 class="d-title"><?= e($product['name']) ?></h1>
                <div class="d-meta">
                    <span class="rating-badge"><?= number_format((float) $product['rating'], 1) ?> ★ <span style="font-weight:600; opacity:.8;">(<?= count($reviews) ?> reviews)</span></span>
                    <span style="color: var(--ink-soft); font-size: 13.5px;"><?= (int) $product['stock'] > 0 ? (int) $product['stock'] . ' in stock' : 'Out of stock' ?></span>
                </div>
                <div class="d-price-row">
                    <span class="d-price"><?= money($product['price']) ?></span>
                    <?php if ($off > 0): ?><span class="price-mrp" style="font-size:16px;"><?= money($product['mrp']) ?></span><span class="price-off"><?= $off ?>% off</span><?php endif; ?>
                </div>
                <p class="trust-line"><?= icon_check() ?> Free Delivery • Cash on Delivery Available • Easy 7-Day Returns</p>
            </div>

            <div class="panel">
                <span class="info-label">Description</span>
                <p class="info-text"><?= e($product['description']) ?></p>
                <br>
                <span class="info-label">Key Ingredients</span>
                <div class="chip-list">
                    <?php foreach ($ingredients as $ing): ?><span class="chip"><?= e($ing) ?></span><?php endforeach; ?>
                </div>
            </div>

            <div class="panel">
                <span class="info-label" style="margin-bottom:14px;">Quantity</span>
                <div class="qty-stepper" data-target="#qtyInput">
                    <button type="button" data-action="dec">−</button>
                    <span>1</span>
                    <button type="button" data-action="inc">+</button>
                </div>

                <div class="btn-group" style="margin-top: 20px;">
                    <?php if ($user): ?>
                        <form class="js-cart-form" action="/cart/add" method="post" style="flex:1;">
                            <?php csrf(); ?>
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" id="qtyInput" name="quantity" value="1">
                            <input type="hidden" name="redirect" value="/product/<?= e($product['slug']) ?>">
                            <button type="submit" class="btn btn-outline btn-block"><?= icon_cart() ?> Add to Cart</button>
                        </form>
                        <form action="/cart/add" method="post" style="flex:1;" onsubmit="document.getElementById('buyNowQty').value = document.querySelector('.qty-stepper span').textContent;">
                            <?php csrf(); ?>
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" id="buyNowQty" name="quantity" value="1">
                            <input type="hidden" name="redirect" value="/checkout">
                            <button type="submit" class="btn btn-dark btn-block">⚡ Buy Now</button>
                        </form>
                    <?php else: ?>
                        <a href="/login" class="btn btn-outline btn-block"><?= icon_cart() ?> Add to Cart</a>
                        <a href="/login" class="btn btn-dark btn-block">⚡ Buy Now</a>
                    <?php endif; ?>
                    <?php if ($user): ?>
                        <form class="js-wishlist-form" action="/wishlist/toggle" method="post">
                            <?php csrf(); ?>
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="redirect" value="/product/<?= e($product['slug']) ?>">
                            <button type="submit" class="btn btn-icon btn-outline wishlist-btn <?= $isWishlisted ? 'active' : '' ?>" style="position:static;"><?= icon_heart() ?></button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="panel">
                <div class="reviews-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 6px;">
                    <span class="info-label" style="margin:0;">Customer Reviews</span>
                </div>
                <?php if (empty($reviews)): ?>
                    <p class="info-text">No reviews yet.</p>
                <?php endif; ?>
                <?php foreach ($reviews as $r): ?>
                    <div class="review-item">
                        <div class="reviewer-row">
                            <span class="reviewer-avatar"><?= e(strtoupper(substr($r['reviewer_name'], 0, 1))) ?></span>
                            <span class="reviewer-name"><?= e($r['reviewer_name']) ?></span>
                            <span class="mini-stars"><?= $r['rating'] ?> ★</span>
                        </div>
                        <p class="review-text"><?= e($r['review_text']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($related)): ?>
        <div class="section-title"><h2>You May Also Like</h2></div>
        <div class="products-grid" style="margin-bottom: 60px;">
            <?php foreach ($related as $p): $roff = $p['mrp'] > $p['price'] ? round((1 - $p['price'] / $p['mrp']) * 100) : 0; ?>
                <div class="product-card">
                    <a href="/product/<?= e($p['slug']) ?>" class="product-thumb">
                        <img src="<?= asset('images/products/' . $p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
                    </a>
                    <?php if ($roff > 0): ?><span class="discount-pill"><?= $roff ?>% OFF</span><?php endif; ?>
                    <a href="/product/<?= e($p['slug']) ?>" class="product-info">
                        <span class="product-cat"><?= category_label($p['category']) ?></span>
                        <span class="product-name"><?= e($p['name']) ?></span>
                        <div class="price-row"><span class="price-now"><?= money($p['price']) ?></span></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
