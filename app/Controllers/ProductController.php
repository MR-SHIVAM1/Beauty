<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Response;
use App\Core\View;
use App\Models\Product;
use App\Models\Wishlist;

class ProductController
{
    public function show(array $params): void
    {
        $product = Product::findBySlug($params['slug']);
        if (!$product) {
            Response::notFound();
            return;
        }

        $reviews = Product::reviews((int) $product['id']);
        $related = Product::related($product['category'], (int) $product['id']);
        $isWishlisted = Auth::check() && Wishlist::has(Auth::id(), (int) $product['id']);

        View::render('product-details', [
            'title' => $product['name'] . ' | Honey Beauty',
            'product' => $product,
            'reviews' => $reviews,
            'related' => $related,
            'isWishlisted' => $isWishlisted,
            'ingredients' => json_decode($product['ingredients'] ?? '[]', true) ?: [],
        ]);
    }
}
