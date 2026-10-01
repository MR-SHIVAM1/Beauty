<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Order;
use App\Models\User;
use App\Models\Wishlist;

class ProfileController
{
    public function index(): void
    {
        Auth::requireLogin();
        $userId = Auth::id();
        View::render('profile', [
            'title' => 'My Profile | Honey Beauty',
            'user' => User::find($userId),
            'orderCount' => count(Order::forUser($userId)),
            'wishlistCount' => count(Wishlist::items($userId)),
        ]);
    }

    public function update(): void
    {
        Auth::requireLogin();
        Csrf::requireValid();
        $name = Request::input('name', '');
        $phone = Request::input('phone', '');

        if (strlen($name) < 2) {
            Flash::set('error', 'Please enter your full name.');
            Response::redirect('/profile');
        }

        User::updateProfile(Auth::id(), $name, $phone);
        Flash::set('success', 'Profile updated successfully.');
        Response::redirect('/profile');
    }
}
