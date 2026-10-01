<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Order;

class OrderController
{
    private const VALID_STATUSES = ['awaiting', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];

    public function index(): void
    {
        Auth::requireAdmin();
        $status = Request::input('status', 'all') ?: 'all';
        View::render('admin/orders/index', [
            'title' => 'Manage Orders | Honey Beauty',
            'orders' => Order::allForAdmin($status),
            'status' => $status,
        ], 'admin/layout');
    }

    public function show(array $params): void
    {
        Auth::requireAdmin();
        $order = Order::find((int) $params['id']);
        if (!$order) {
            Response::notFound();
            return;
        }
        View::render('admin/orders/show', [
            'title' => 'Order ' . $order['order_number'] . ' | Honey Beauty',
            'order' => $order,
            'items' => Order::items((int) $order['id']),
        ], 'admin/layout');
    }

    public function updateStatus(array $params): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        $id = (int) $params['id'];
        $status = Request::input('status', '');
        if (!in_array($status, self::VALID_STATUSES, true)) {
            Flash::set('error', 'Invalid order status.');
            Response::redirect('/admin/orders');
        }
        Order::updateStatus($id, $status);
        Flash::set('success', 'Order status updated.');
        Response::redirect('/admin/orders/' . $id);
    }
}
