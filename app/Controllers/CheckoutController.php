<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Cart;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\User;

class CheckoutController
{
    public function index(): void
    {
        Auth::requireLogin();
        $items = Cart::items(Auth::id());
        if (empty($items)) {
            Flash::set('error', 'Your cart is empty. Add something you love first!');
            Response::redirect('/cart');
        }

        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
        $promo = $_SESSION['promo'] ?? null;
        $discount = $promo ? (int) round($subtotal * $promo['discount_percent'] / 100) : 0;
        $total = max(0, $subtotal - $discount);

        View::render('checkout', [
            'title' => 'Checkout | Honey Beauty',
            'items' => $items,
            'subtotal' => $subtotal,
            'promo' => $promo,
            'discount' => $discount,
            'total' => $total,
            'user' => User::find(Auth::id()),
        ]);
    }

    public function applyPromo(): void
    {
        Auth::requireLogin();
        Csrf::requireValid();
        $code = Request::input('promo_code', '');
        $promo = PromoCode::findActiveByCode($code);

        if (!$promo) {
            Flash::set('error', 'That promo code is invalid or expired.');
            $_SESSION['promo'] = null;
        } else {
            $_SESSION['promo'] = ['code' => $promo['code'], 'discount_percent' => (int) $promo['discount_percent']];
            Flash::set('success', $promo['code'] . ' applied — ' . $promo['discount_percent'] . '% off!');
        }
        Response::redirect('/checkout');
    }

    public function removePromo(): void
    {
        Auth::requireLogin();
        Csrf::requireValid();
        unset($_SESSION['promo']);
        Response::redirect('/checkout');
    }

    public function place(): void
    {
        Auth::requireLogin();
        Csrf::requireValid();

        $items = Cart::items(Auth::id());
        if (empty($items)) {
            Flash::set('error', 'Your cart is empty.');
            Response::redirect('/cart');
        }

        $address = [
            'name' => Request::input('c_name', ''),
            'phone' => Request::input('c_phone', ''),
            'line' => Request::input('c_address', ''),
            'pincode' => Request::input('c_pincode', ''),
        ];
        $paymentMethod = Request::input('payment_method', 'card');

        $errors = [];
        if (strlen($address['name']) < 2) $errors[] = 'Please enter the recipient name.';
        if (!preg_match('/^\d{10}$/', $address['phone'])) $errors[] = 'Please enter a valid 10-digit mobile number.';
        if (strlen($address['line']) < 5) $errors[] = 'Please enter a complete delivery address.';
        if (!preg_match('/^\d{6}$/', $address['pincode'])) $errors[] = 'Please enter a valid 6-digit pincode.';

        $paymentMeta = [];
        if ($paymentMethod === 'card') {
            $cardNumber = preg_replace('/\s+/', '', Request::input('card_number', ''));
            $cardExpiry = Request::input('card_expiry', '');
            $cardCvv = Request::input('card_cvv', '');
            $cardName = Request::input('card_name', '');
            if (!preg_match('/^\d{16}$/', $cardNumber)) $errors[] = 'Please enter a valid 16-digit demo card number.';
            if (!preg_match('#^(0[1-9]|1[0-2])/\d{2}$#', $cardExpiry)) $errors[] = 'Please enter a valid expiry as MM/YY.';
            if (!preg_match('/^\d{3,4}$/', $cardCvv)) $errors[] = 'Please enter a valid CVV.';
            if (strlen($cardName) < 2) $errors[] = 'Please enter the name on card.';
            $paymentMeta = ['card_last4' => substr($cardNumber, -4), 'card_name' => $cardName];
        } elseif ($paymentMethod === 'upi') {
            $upiId = Request::input('upi_id', '');
            if (!preg_match('/^[\w.\-]{2,}@[a-zA-Z]{2,}$/', $upiId)) $errors[] = 'Please enter a valid UPI ID (e.g. name@bank).';
            $paymentMeta = ['upi_id' => $upiId];
        } elseif ($paymentMethod === 'cod') {
            $paymentMeta = ['note' => 'Cash on Delivery'];
        } else {
            $errors[] = 'Please select a payment method.';
        }

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            Response::redirect('/checkout');
        }

        $promo = $_SESSION['promo'] ?? null;
        $order = Order::create(Auth::id(), $items, $address, $paymentMethod, $paymentMeta, $promo);

        Cart::clear(Auth::id());
        unset($_SESSION['promo']);

        Response::redirect('/order/success/' . $order['order_number']);
    }

    public function success(array $params): void
    {
        Auth::requireLogin();
        $order = Order::findByNumberForUser($params['number'], Auth::id());
        if (!$order) {
            Response::notFound();
            return;
        }
        View::render('order-success', [
            'title' => 'Order Confirmed | Honey Beauty',
            'order' => $order,
            'items' => Order::items((int) $order['id']),
        ]);
    }
}
