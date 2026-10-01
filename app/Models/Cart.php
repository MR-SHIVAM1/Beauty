<?php
namespace App\Models;

use App\Core\Database;

class Cart
{
    public static function items(int $userId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT ci.id AS cart_id, ci.quantity, p.* FROM cart_items ci
             JOIN products p ON p.id = ci.product_id
             WHERE ci.user_id = ? ORDER BY ci.id DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function add(int $userId, int $productId, int $quantity = 1): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)
             ON CONFLICT(user_id, product_id) DO UPDATE SET quantity = quantity + excluded.quantity'
        );
        $stmt->execute([$userId, $productId, $quantity]);
    }

    public static function updateQuantity(int $userId, int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            self::remove($userId, $productId);
            return;
        }
        $stmt = Database::connection()->prepare(
            'UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?'
        );
        $stmt->execute([$quantity, $userId, $productId]);
    }

    public static function remove(int $userId, int $productId): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM cart_items WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$userId, $productId]);
    }

    public static function clear(int $userId): void
    {
        Database::connection()->prepare('DELETE FROM cart_items WHERE user_id = ?')->execute([$userId]);
    }

    public static function count(int $userId): int
    {
        $stmt = Database::connection()->prepare('SELECT COALESCE(SUM(quantity), 0) c FROM cart_items WHERE user_id = ?');
        $stmt->execute([$userId]);
        return (int) $stmt->fetch()['c'];
    }
}
