<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Response;
use App\Core\View;
use App\Models\Order;

class OrderController
{
    public function index(): void
    {
        Auth::requireLogin();
        View::render('orders', [
            'title' => 'My Orders | Honey Beauty',
            'orders' => Order::forUser(Auth::id()),
        ]);
    }

    public function show(array $params): void
    {
        Auth::requireLogin();
        $order = Order::findByNumberForUser($params['number'], Auth::id());
        if (!$order) {
            Response::notFound();
            return;
        }
        View::render('order-detail', [
            'title' => 'Order ' . $order['order_number'] . ' | Honey Beauty',
            'order' => $order,
            'items' => Order::items((int) $order['id']),
        ]);
    }
}
