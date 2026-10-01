<?php
namespace App\Models;

use App\Core\Database;

class User
{
    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([strtolower($email)]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function create(string $name, string $email, string $phone, string $password, string $role = 'customer'): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$name, strtolower($email), $phone, password_hash($password, PASSWORD_BCRYPT), $role]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function updateProfile(int $id, string $name, string $phone): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET name = ?, phone = ? WHERE id = ?');
        $stmt->execute([$name, $phone, $id]);
    }

    public static function allAdmins(): array
    {
        $stmt = Database::connection()->prepare("SELECT * FROM users WHERE role = 'admin' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function allCustomers(): array
    {
        $stmt = Database::connection()->prepare("SELECT * FROM users WHERE role = 'customer' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function promoteToAdmin(int $id): void
    {
        Database::connection()->prepare("UPDATE users SET role = 'admin' WHERE id = ?")->execute([$id]);
    }

    public static function countAll(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) c FROM users')->fetch()['c'];
    }
}
