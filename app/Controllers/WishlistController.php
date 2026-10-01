<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController
{
    public function index(): void
    {
        Auth::requireLogin();
        View::render('wishlist', [
            'title' => 'Your Wishlist | Honey Beauty',
            'items' => Wishlist::items(Auth::id()),
        ]);
    }

    public function toggle(): void
    {
        Auth::requireLogin();
        Csrf::requireValid();
        $productId = (int) Request::input('product_id');
        $product = Product::find($productId);
        if (!$product) {
            Response::notFound();
            return;
        }
        $added = Wishlist::toggle(Auth::id(), $productId);
        $message = $added ? $product['name'] . ' added to wishlist.' : $product['name'] . ' removed from wishlist.';

        if (Request::isAjax()) {
            Response::json(['ok' => true, 'added' => $added, 'message' => $message, 'wishlistCount' => count(Wishlist::items(Auth::id()))]);
        }

        Flash::set('success', $message);
        Response::redirect(Request::input('redirect', '/product/' . $product['slug']));
    }
}
