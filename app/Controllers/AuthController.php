<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\User;

class AuthController
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            Response::redirect(Auth::isAdmin() ? '/admin' : '/');
        }
        View::render('auth/login', ['title' => 'Sign In | Honey Beauty'], 'auth-layout');
    }

    public function login(): void
    {
        Csrf::requireValid();
        $identifier = Request::input('email');
        $password = Request::input('password', '');

        if ($identifier === '' || $password === '') {
            Flash::set('error', 'Please enter both email/username and password.');
            Response::redirect('/login');
        }

        $email = strtolower($identifier) === 'admin' ? 'admin@honeybeauty.local' : $identifier;
        $user = Auth::attempt($email, $password);

        if (!$user) {
            Flash::set('error', 'Invalid credentials. Please try again.');
            Response::redirect('/login');
        }

        Flash::set('success', 'Welcome back, ' . $user['name'] . '!');
        Response::redirect($user['role'] === 'admin' ? '/admin' : '/');
    }

    public function signupForm(): void
    {
        if (Auth::check()) {
            Response::redirect(Auth::isAdmin() ? '/admin' : '/');
        }
        View::render('auth/signup', ['title' => 'Create Account | Honey Beauty'], 'auth-layout');
    }

    public function signup(): void
    {
        Csrf::requireValid();
        $name = Request::input('name');
        $email = Request::input('email');
        $phone = Request::input('phone');
        $password = Request::input('password', '');
        $confirm = Request::input('password_confirmation', '');

        $errors = [];
        if (strlen($name) < 2) $errors[] = 'Please enter your full name.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
        if (strtolower(trim($email)) === 'admin@honeybeauty.local') $errors[] = 'This email is reserved.';
        if (strlen($phone) < 8) $errors[] = 'Please enter a valid mobile number.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';
        if (User::findByEmail($email)) $errors[] = 'An account with this email already exists.';

        if (!empty($errors)) {
            $_SESSION['old'] = ['name' => $name, 'email' => $email, 'phone' => $phone];
            Flash::set('error', implode(' ', $errors));
            Response::redirect('/signup');
        }

        $userId = User::create($name, $email, $phone, $password, 'customer');
        Auth::login(User::find($userId));
        unset($_SESSION['old']);
        Flash::set('success', 'Account created! Welcome to Honey Beauty, ' . $name . '.');
        Response::redirect('/');
    }

    public function logout(): void
    {
        Auth::logout();
        Response::redirect('/login');
    }
}
