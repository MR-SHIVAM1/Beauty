<?php
/** @var App\Core\Router $router */

use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\OrderController as AdminOrderController;
use App\Controllers\Admin\ProductController as AdminProductController;
use App\Controllers\Admin\PromoController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\HomeController;
use App\Controllers\OrderController;
use App\Controllers\ProductController;
use App\Controllers\ProfileController;
use App\Controllers\WishlistController;

// Storefront
$router->get('/', fn() => (new HomeController())->index());
$router->get('/product/{slug}', fn($p) => (new ProductController())->show($p));

// Auth
$router->get('/login', fn() => (new AuthController())->loginForm());
$router->post('/login', fn() => (new AuthController())->login());
$router->get('/signup', fn() => (new AuthController())->signupForm());
$router->post('/signup', fn() => (new AuthController())->signup());
$router->post('/logout', fn() => (new AuthController())->logout());

// Cart
$router->get('/cart', fn() => (new CartController())->index());
$router->post('/cart/add', fn() => (new CartController())->add());
$router->post('/cart/update', fn() => (new CartController())->update());
$router->post('/cart/remove', fn() => (new CartController())->remove());

// Wishlist
$router->get('/wishlist', fn() => (new WishlistController())->index());
$router->post('/wishlist/toggle', fn() => (new WishlistController())->toggle());

// Checkout
$router->get('/checkout', fn() => (new CheckoutController())->index());
$router->post('/checkout/promo', fn() => (new CheckoutController())->applyPromo());
$router->post('/checkout/promo/remove', fn() => (new CheckoutController())->removePromo());
$router->post('/checkout/place', fn() => (new CheckoutController())->place());
$router->get('/order/success/{number}', fn($p) => (new CheckoutController())->success($p));

// Customer orders & profile
$router->get('/orders', fn() => (new OrderController())->index());
$router->get('/orders/{number}', fn($p) => (new OrderController())->show($p));
$router->get('/profile', fn() => (new ProfileController())->index());
$router->post('/profile/update', fn() => (new ProfileController())->update());

// Admin
$router->get('/admin', fn() => (new DashboardController())->index());
$router->get('/admin/products', fn() => (new AdminProductController())->index());
$router->get('/admin/products/new', fn() => (new AdminProductController())->create());
$router->post('/admin/products', fn() => (new AdminProductController())->store());
$router->get('/admin/products/{id}/edit', fn($p) => (new AdminProductController())->edit($p));
$router->post('/admin/products/{id}', fn($p) => (new AdminProductController())->update($p));
$router->post('/admin/products/{id}/delete', fn($p) => (new AdminProductController())->destroy($p));

$router->get('/admin/orders', fn() => (new AdminOrderController())->index());
$router->get('/admin/orders/{id}', fn($p) => (new AdminOrderController())->show($p));
$router->post('/admin/orders/{id}/status', fn($p) => (new AdminOrderController())->updateStatus($p));

$router->get('/admin/promos', fn() => (new PromoController())->index());
$router->post('/admin/promos', fn() => (new PromoController())->store());
$router->post('/admin/promos/{id}/toggle', fn($p) => (new PromoController())->toggle($p));
$router->post('/admin/promos/{id}/delete', fn($p) => (new PromoController())->destroy($p));

$router->get('/admin/admins', fn() => (new AdminController())->index());
$router->post('/admin/admins', fn() => (new AdminController())->store());
