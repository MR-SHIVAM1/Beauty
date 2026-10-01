<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;
use App\Core\Str;
use App\Core\View;
use App\Models\Product;

class ProductController
{
    private const ALLOWED_TYPES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    public function index(): void
    {
        Auth::requireAdmin();
        View::render('admin/products/index', [
            'title' => 'Manage Products | Honey Beauty',
            'products' => Product::allForAdmin(),
        ], 'admin/layout');
    }

    public function create(): void
    {
        Auth::requireAdmin();
        View::render('admin/products/form', [
            'title' => 'Add Product | Honey Beauty',
            'product' => null,
        ], 'admin/layout');
    }

    public function store(): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        $data = $this->collectInput();

        if (!empty($data['errors'])) {
            Flash::set('error', implode(' ', $data['errors']));
            Response::redirect('/admin/products/new');
        }

        $image = $this->handleUpload();
        if (!$image) {
            Flash::set('error', 'Please upload a product image (jpg, png, or webp).');
            Response::redirect('/admin/products/new');
        }
        $data['image'] = $image;

        Product::create($data);
        Flash::set('success', $data['name'] . ' was added to the catalog.');
        Response::redirect('/admin/products');
    }

    public function edit(array $params): void
    {
        Auth::requireAdmin();
        $product = Product::find((int) $params['id']);
        if (!$product) {
            Response::notFound();
            return;
        }
        View::render('admin/products/form', [
            'title' => 'Edit Product | Honey Beauty',
            'product' => $product,
        ], 'admin/layout');
    }

    public function update(array $params): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        $id = (int) $params['id'];
        $product = Product::find($id);
        if (!$product) {
            Response::notFound();
            return;
        }

        $data = $this->collectInput($id);
        if (!empty($data['errors'])) {
            Flash::set('error', implode(' ', $data['errors']));
            Response::redirect('/admin/products/' . $id . '/edit');
        }

        $image = $this->handleUpload();
        $data['image'] = $image ?: $product['image'];

        Product::update($id, $data);
        Flash::set('success', $data['name'] . ' was updated.');
        Response::redirect('/admin/products');
    }

    public function destroy(array $params): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        Product::delete((int) $params['id']);
        Flash::set('success', 'Product deleted.');
        Response::redirect('/admin/products');
    }

    private function collectInput(?int $excludeId = null): array
    {
        $name = Request::input('name', '');
        $category = Request::input('category', 'makeup');
        $price = (int) Request::input('price', 0);
        $mrp = (int) Request::input('mrp', 0);
        $stock = (int) Request::input('stock', 0);
        $shortDesc = Request::input('short_desc', '');
        $description = Request::input('description', '');
        $ingredientsRaw = Request::input('ingredients', '');
        $isActive = Request::input('is_active') ? 1 : 0;

        $ingredients = array_values(array_filter(array_map('trim', explode(',', $ingredientsRaw))));

        $errors = [];
        if (strlen($name) < 2) $errors[] = 'Please enter a product name.';
        if (!in_array($category, ['makeup', 'skincare', 'tools'], true)) $errors[] = 'Please choose a valid category.';
        if ($price <= 0) $errors[] = 'Please enter a valid price.';
        if ($mrp <= 0 || $mrp < $price) $errors[] = 'MRP must be greater than or equal to price.';

        $slug = Str::slugify($name);
        if (Product::slugExists($slug, $excludeId)) {
            $slug .= '-' . substr(md5((string) microtime(true)), 0, 4);
        }

        return [
            'name' => $name,
            'slug' => $slug,
            'category' => $category,
            'price' => $price,
            'mrp' => $mrp,
            'stock' => $stock,
            'short_desc' => $shortDesc,
            'description' => $description,
            'ingredients' => $ingredients,
            'is_active' => $isActive,
            'errors' => $errors,
        ];
    }

    private function handleUpload(): ?string
    {
        $file = Request::file('product_image');
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $mime = mime_content_type($file['tmp_name']);
        if (!isset(self::ALLOWED_TYPES[$mime])) {
            return null;
        }
        $ext = self::ALLOWED_TYPES[$mime];
        $filename = 'upload-' . bin2hex(random_bytes(6)) . '.' . $ext;
        $destination = dirname(__DIR__, 3) . '/public/assets/images/products/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }
        return $filename;
    }
}
