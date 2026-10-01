<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\View;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController
{
    public function index(): void
    {
        Auth::requireAdmin();
        View::render('admin/dashboard', [
            'title' => 'Admin Dashboard | Honey Beauty',
            'productCount' => Product::countAll(),
            'orderCount' => Order::countAll(),
            'userCount' => User::countAll(),
            'revenue' => Order::totalRevenue(),
            'statusCounts' => [
                'awaiting' => Order::countByStatus('awaiting'),
                'processing' => Order::countByStatus('processing'),
                'out_for_delivery' => Order::countByStatus('out_for_delivery'),
                'delivered' => Order::countByStatus('delivered'),
            ],
            'recentOrders' => array_slice(Order::allForAdmin(), 0, 6),
        ], 'admin/layout');
    }
}
