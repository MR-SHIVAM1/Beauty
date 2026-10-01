<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\PromoCode;

class PromoController
{
    public function index(): void
    {
        Auth::requireAdmin();
        View::render('admin/promos/index', [
            'title' => 'Promo Codes | Honey Beauty',
            'promos' => PromoCode::all(),
        ], 'admin/layout');
    }

    public function store(): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        $code = strtoupper(trim(Request::input('code', '')));
        $discount = (int) Request::input('discount_percent', 0);

        if ($code === '' || $discount <= 0 || $discount > 90) {
            Flash::set('error', 'Please provide a valid code and a discount between 1-90%.');
            Response::redirect('/admin/promos');
        }

        try {
            PromoCode::create($code, $discount);
            Flash::set('success', 'Promo code ' . $code . ' created.');
        } catch (\PDOException $e) {
            Flash::set('error', 'That promo code already exists.');
        }
        Response::redirect('/admin/promos');
    }

    public function toggle(array $params): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        PromoCode::toggle((int) $params['id']);
        Response::redirect('/admin/promos');
    }

    public function destroy(array $params): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        PromoCode::delete((int) $params['id']);
        Flash::set('success', 'Promo code deleted.');
        Response::redirect('/admin/promos');
    }
}
