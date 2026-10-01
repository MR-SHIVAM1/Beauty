<div class="admin-topbar">
    <h1>Products</h1>
    <a href="/admin/products/new" class="btn btn-primary btn-sm">+ Add Product</a>
</div>

<div class="admin-panel">
    <?php if (empty($products)): ?>
        <p class="empty-admin">No products yet. Add your first one!</p>
    <?php else: ?>
        <div class="table-scroll">
            <table class="data-table">
                <thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><img class="table-thumb" src="<?= asset('images/products/' . $p['image']) ?>" alt=""></td>
                        <td><strong><?= e($p['name']) ?></strong></td>
                        <td><?= category_label($p['category']) ?></td>
                        <td><?= money($p['price']) ?></td>
                        <td><?= (int) $p['stock'] ?></td>
                        <td><span class="toggle-pill <?= $p['is_active'] ? 'on' : 'off' ?>"><?= $p['is_active'] ? 'Active' : 'Hidden' ?></span></td>
                        <td style="white-space:nowrap;">
                            <a href="/admin/products/<?= $p['id'] ?>/edit" class="icon-action" title="Edit"><?= icon_edit() ?></a>
                            <form action="/admin/products/<?= $p['id'] ?>/delete" method="post" style="display:inline;" onsubmit="return confirm('Delete this product permanently?');">
                                <?php csrf(); ?>
                                <button type="submit" class="icon-action danger" title="Delete"><?= icon_trash() ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
