<?php
namespace App\Models;

use App\Core\Database;

class Wishlist
{
    public static function items(int $userId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT p.* FROM wishlists w JOIN products p ON p.id = w.product_id
             WHERE w.user_id = ? ORDER BY w.id DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function has(int $userId, int $productId): bool
    {
        $stmt = Database::connection()->prepare('SELECT COUNT(*) c FROM wishlists WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$userId, $productId]);
        return (int) $stmt->fetch()['c'] > 0;
    }

    public static function toggle(int $userId, int $productId): bool
    {
        if (self::has($userId, $productId)) {
            Database::connection()->prepare('DELETE FROM wishlists WHERE user_id = ? AND product_id = ?')
                ->execute([$userId, $productId]);
            return false;
        }
        Database::connection()->prepare('INSERT INTO wishlists (user_id, product_id) VALUES (?, ?)')
            ->execute([$userId, $productId]);
        return true;
    }

    public static function idsForUser(int $userId): array
    {
        $stmt = Database::connection()->prepare('SELECT product_id FROM wishlists WHERE user_id = ?');
        $stmt->execute([$userId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'product_id'));
    }
}
