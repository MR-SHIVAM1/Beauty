<?php
$isEdit = $product !== null;
$ingredientsStr = $isEdit ? implode(', ', json_decode($product['ingredients'] ?? '[]', true) ?: []) : '';
?>
<div class="admin-topbar">
    <h1><?= $isEdit ? 'Edit Product' : 'Add Product' ?></h1>
    <a href="/admin/products" class="back-store-link">← Back to Products</a>
</div>

<form action="<?= $isEdit ? '/admin/products/' . $product['id'] : '/admin/products' ?>" method="post" enctype="multipart/form-data">
    <?php csrf(); ?>
    <div class="admin-form-grid">
        <div class="admin-panel">
            <div class="field"><label>Product Name</label><input type="text" name="name" value="<?= e($product['name'] ?? '') ?>" required></div>
            <div class="field-row">
                <div class="field">
                    <label>Category</label>
                    <select name="category" required>
                        <?php foreach (['makeup' => 'Makeup', 'skincare' => 'Skincare', 'tools' => 'Tools & Brushes'] as $val => $label): ?>
                            <option value="<?= $val ?>" <?= ($product['category'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field"><label>Stock Quantity</label><input type="number" name="stock" value="<?= e($product['stock'] ?? 50) ?>" min="0" required></div>
            </div>
            <div class="field-row">
                <div class="field"><label>Price (₹)</label><input type="number" name="price" value="<?= e($product['price'] ?? '') ?>" min="1" required></div>
                <div class="field"><label>MRP (₹)</label><input type="number" name="mrp" value="<?= e($product['mrp'] ?? '') ?>" min="1" required></div>
            </div>
            <div class="field"><label>Short Description</label><input type="text" name="short_desc" value="<?= e($product['short_desc'] ?? '') ?>" placeholder="One-line highlight shown on listing pages"></div>
            <div class="field"><label>Full Description</label><textarea name="description" rows="4" placeholder="Detailed product description"><?= e($product['description'] ?? '') ?></textarea></div>
            <div class="field"><label>Key Ingredients (comma-separated)</label><input type="text" name="ingredients" value="<?= e($ingredientsStr) ?>" placeholder="Vitamin E, Aloe Vera, Shea Butter"></div>
            <div class="field">
                <label style="display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" name="is_active" value="1" style="width:auto;" <?= ($product['is_active'] ?? 1) ? 'checked' : '' ?>>
                    Visible in store
                </label>
            </div>
        </div>

        <div class="admin-panel">
            <span class="info-label">Product Image</span>
            <div class="image-drop">
                <img id="imagePreview" src="<?= $isEdit ? asset('images/products/' . $product['image']) : 'https://placehold.co/400x400/fbf5ee/b8863b?text=Preview' ?>" alt="Preview">
                <input type="file" id="product_image" name="product_image" accept="image/jpeg,image/png,image/webp">
                <p style="font-size:12px; color:var(--ink-faint); margin-top:8px;"><?= $isEdit ? 'Leave empty to keep the current image.' : 'JPG, PNG or WEBP.' ?></p>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="margin-top:18px;"><?= $isEdit ? 'Save Changes' : 'Add Product' ?></button>
        </div>
    </div>
</form>
