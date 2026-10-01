<?php
namespace App\Models;

use App\Core\Database;

class Order
{
    public static function create(int $userId, array $cartItems, array $address, string $paymentMethod, array $paymentMeta, ?array $promo): array
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $discount = $promo ? (int) round($subtotal * $promo['discount_percent'] / 100) : 0;
            $total = max(0, $subtotal - $discount);
            $orderNumber = 'HB' . date('ymd') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));

            $stmt = $pdo->prepare(
                'INSERT INTO orders (order_number, user_id, subtotal, discount, total, promo_code, status, payment_method, payment_meta,
                 address_name, address_phone, address_line, address_pincode)
                 VALUES (?, ?, ?, ?, ?, ?, "awaiting", ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $orderNumber, $userId, $subtotal, $discount, $total,
                $promo['code'] ?? null, $paymentMethod, json_encode($paymentMeta),
                $address['name'], $address['phone'], $address['line'], $address['pincode'],
            ]);
            $orderId = (int) $pdo->lastInsertId();

            $itemStmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, product_image, price, quantity) VALUES (?, ?, ?, ?, ?, ?)'
            );
            foreach ($cartItems as $item) {
                $itemStmt->execute([$orderId, $item['id'], $item['name'], $item['image'], $item['price'], $item['quantity']]);
                Product::decrementStock((int) $item['id'], (int) $item['quantity']);
            }

            $pdo->commit();
            return self::find($orderId);
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public static function findByNumberForUser(string $number, int $userId): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM orders WHERE order_number = ? AND user_id = ?');
        $stmt->execute([$number, $userId]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public static function items(int $orderId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM order_items WHERE order_id = ?');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public static function forUser(int $userId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function allForAdmin(?string $status = null): array
    {
        if ($status && $status !== 'all') {
            $stmt = Database::connection()->prepare(
                'SELECT o.*, u.name AS customer_name, u.email AS customer_email FROM orders o
                 JOIN users u ON u.id = o.user_id WHERE o.status = ? ORDER BY o.id DESC'
            );
            $stmt->execute([$status]);
        } else {
            $stmt = Database::connection()->query(
                'SELECT o.*, u.name AS customer_name, u.email AS customer_email FROM orders o
                 JOIN users u ON u.id = o.user_id ORDER BY o.id DESC'
            );
        }
        return $stmt->fetchAll();
    }

    public static function updateStatus(int $id, string $status): void
    {
        $stmt = Database::connection()->prepare(
            "UPDATE orders SET status = ?, updated_at = datetime('now') WHERE id = ?"
        );
        $stmt->execute([$status, $id]);
    }

    public static function countAll(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) c FROM orders')->fetch()['c'];
    }

    public static function totalRevenue(): int
    {
        return (int) Database::connection()->query("SELECT COALESCE(SUM(total),0) t FROM orders WHERE status != 'cancelled'")->fetch()['t'];
    }

    public static function countByStatus(string $status): int
    {
        $stmt = Database::connection()->prepare('SELECT COUNT(*) c FROM orders WHERE status = ?');
        $stmt->execute([$status]);
        return (int) $stmt->fetch()['c'];
    }
}
