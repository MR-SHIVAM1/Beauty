<?php
namespace App\Core;

class Flash
{
    public static function set(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    public static function get(string $type): ?string
    {
        if (empty($_SESSION['flash'][$type])) {
            return null;
        }
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }

    public static function all(): array
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }
}
