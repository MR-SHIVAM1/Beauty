<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Cart;
use App\Models\Product;

class CartController
{
    public function index(): void
    {
        Auth::requireLogin();
        $items = Cart::items(Auth::id());
        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));

        View::render('cart', [
            'title' => 'Your Cart | Honey Beauty',
            'items' => $items,
            'subtotal' => $subtotal,
        ]);
    }

    public function add(): void
    {
        Auth::requireLogin();
        Csrf::requireValid();
        $productId = (int) Request::input('product_id');
        $product = Product::find($productId);
        if (!$product) {
            Response::notFound();
            return;
        }
        $quantity = max(1, (int) Request::input('quantity', 1));
        Cart::add(Auth::id(), $productId, $quantity);

        if (Request::isAjax()) {
            Response::json(['ok' => true, 'message' => $product['name'] . ' added to cart.', 'cartCount' => Cart::count(Auth::id())]);
        }

        Flash::set('success', $product['name'] . ' added to cart.');
        Response::redirect(Request::input('redirect', '/product/' . $product['slug']));
    }

    public function update(): void
    {
        Auth::requireLogin();
        Csrf::requireValid();
        $productId = (int) Request::input('product_id');
        $quantity = max(0, (int) Request::input('quantity', 1));
        Cart::updateQuantity(Auth::id(), $productId, $quantity);
        Response::redirect('/cart');
    }

    public function remove(): void
    {
        Auth::requireLogin();
        Csrf::requireValid();
        $productId = (int) Request::input('product_id');
        Cart::remove(Auth::id(), $productId);
        Flash::set('success', 'Item removed from cart.');
        Response::redirect('/cart');
    }
}
