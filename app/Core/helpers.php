<?php
/**
 * Global template helpers. mbstring is not available in this environment,
 * so all string handling here intentionally stays byte-safe (ASCII/UTF-8
 * passthrough) rather than using mb_* functions.
 */

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;

require_once __DIR__ . '/../Views/partials/icons.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function money(int $paise): string
{
    return '₹' . number_format($paise);
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function old(string $key, string $default = ''): string
{
    return e($_SESSION['old'][$key] ?? $default);
}

function csrf(): void
{
    echo Csrf::field();
}

function current_user(): ?array
{
    return Auth::user();
}

function is_logged_in(): bool
{
    return Auth::check();
}

function is_admin(): bool
{
    return Auth::isAdmin();
}

function flash($type)
{
    return Flash::get($type);
}

function status_label(string $status): string
{
    return [
        'awaiting' => 'Awaiting Confirmation',
        'processing' => 'Processing',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ][$status] ?? ucfirst($status);
}

function status_step(string $status): int
{
    return [
        'awaiting' => 1,
        'processing' => 2,
        'out_for_delivery' => 3,
        'delivered' => 4,
        'cancelled' => 0,
    ][$status] ?? 0;
}

function category_label(string $category): string
{
    return [
        'makeup' => 'Makeup',
        'skincare' => 'Skincare',
        'tools' => 'Tools & Brushes',
    ][$category] ?? ucfirst($category);
}

function star_rating(float $rating): string
{
    $full = (int) floor($rating);
    $half = ($rating - $full) >= 0.5;
    $html = str_repeat('<i class="fa-solid fa-star"></i>', $full);
    if ($half) {
        $html .= '<i class="fa-solid fa-star-half-stroke"></i>';
    }
    $empty = 5 - $full - ($half ? 1 : 0);
    $html .= str_repeat('<i class="fa-regular fa-star"></i>', max(0, $empty));
    return $html;
}
