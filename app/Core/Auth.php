<?php
namespace App\Core;

use App\Models\User;

class Auth
{
    public static function attempt(string $email, string $password): ?array
    {
        $user = User::findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return null;
        }
        self::login($user);
        return $user;
    }

    public static function login(array $user): void
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        session_regenerate_id(true);
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        return self::check() && ($_SESSION['user_role'] ?? '') === 'admin';
    }

    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function user(): ?array
    {
        static $cached = null;
        static $fetched = false;
        if ($fetched) {
            return $cached;
        }
        $fetched = true;
        if (!self::check()) {
            return null;
        }
        $cached = User::find((int) $_SESSION['user_id']);
        return $cached;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            Flash::set('error', 'Please sign in to continue.');
            Response::redirect('/login');
        }
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            Flash::set('error', 'Admin access required.');
            Response::redirect('/login');
        }
    }
}
