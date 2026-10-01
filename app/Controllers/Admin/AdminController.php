<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\User;

class AdminController
{
    public function index(): void
    {
        Auth::requireAdmin();
        View::render('admin/admins/index', [
            'title' => 'Manage Admins | Honey Beauty',
            'admins' => User::allAdmins(),
            'customers' => User::allCustomers(),
        ], 'admin/layout');
    }

    public function store(): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        $mode = Request::input('mode', 'promote');

        if ($mode === 'promote') {
            $email = Request::input('email', '');
            $user = User::findByEmail($email);
            if (!$user) {
                Flash::set('error', 'No customer account found with that email.');
                Response::redirect('/admin/admins');
            }
            if ($user['role'] === 'admin') {
                Flash::set('error', $user['name'] . ' is already an admin.');
                Response::redirect('/admin/admins');
            }
            User::promoteToAdmin((int) $user['id']);
            Flash::set('success', $user['name'] . ' is now an admin.');
            Response::redirect('/admin/admins');
        }

        // mode === 'create'
        $name = Request::input('name', '');
        $email = Request::input('email', '');
        $password = Request::input('password', '');

        $errors = [];
        if (strlen($name) < 2) $errors[] = 'Please enter a name.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if (User::findByEmail($email)) $errors[] = 'An account with this email already exists.';

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            Response::redirect('/admin/admins');
        }

        User::create($name, $email, Request::input('phone', ''), $password, 'admin');
        Flash::set('success', 'New admin account created for ' . $name . '.');
        Response::redirect('/admin/admins');
    }
}
