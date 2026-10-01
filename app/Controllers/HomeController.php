<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\View;
use App\Models\Product;
use App\Models\Wishlist;

class HomeController
{
    public function index(): void
    {
        $category = Request::input('category', 'all') ?: 'all';
        $search = Request::input('q', '');

        $products = Product::all($category, $search ?: null);
        $wishlistIds = Auth::check() ? Wishlist::idsForUser(Auth::id()) : [];

        View::render('home', [
            'title' => 'Honey Beauty | Premium Beauty & Skincare',
            'products' => $products,
            'category' => $category,
            'search' => $search,
            'wishlistIds' => $wishlistIds,
        ]);
    }
}
