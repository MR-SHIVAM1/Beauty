<?php
namespace App\Models;

use App\Core\Database;

class PromoCode
{
    public static function all(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM promo_codes ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function findActiveByCode(string $code): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM promo_codes WHERE code = ? AND active = 1');
        $stmt->execute([strtoupper(trim($code))]);
        $promo = $stmt->fetch();
        return $promo ?: null;
    }

    public static function create(string $code, int $discountPercent): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO promo_codes (code, discount_percent, active) VALUES (?, ?, 1)'
        );
        $stmt->execute([strtoupper(trim($code)), $discountPercent]);
    }

    public static function toggle(int $id): void
    {
        Database::connection()->prepare('UPDATE promo_codes SET active = 1 - active WHERE id = ?')->execute([$id]);
    }

    public static function delete(int $id): void
    {
        Database::connection()->prepare('DELETE FROM promo_codes WHERE id = ?')->execute([$id]);
    }
}
