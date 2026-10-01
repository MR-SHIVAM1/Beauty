<?php
namespace App\Models;

use App\Core\Database;

class Product
{
    public static function all(?string $category = null, ?string $search = null): array
    {
        $sql = 'SELECT * FROM products WHERE is_active = 1';
        $params = [];
        if ($category && $category !== 'all') {
            $sql .= ' AND category = ?';
            $params[] = $category;
        }
        if ($search) {
            $sql .= ' AND name LIKE ?';
            $params[] = '%' . $search . '%';
        }
        $sql .= ' ORDER BY id ASC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function allForAdmin(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM products ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    public static function findBySlug(string $slug): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM products WHERE slug = ?');
        $stmt->execute([$slug]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    public static function reviews(int $productId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM product_reviews WHERE product_id = ? ORDER BY id DESC');
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public static function related(string $category, int $excludeId, int $limit = 4): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT * FROM products WHERE category = ? AND id != ? AND is_active = 1 ORDER BY RANDOM() LIMIT ?'
        );
        $stmt->bindValue(1, $category);
        $stmt->bindValue(2, $excludeId, \PDO::PARAM_INT);
        $stmt->bindValue(3, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO products (name, slug, category, price, mrp, rating, image, short_desc, description, ingredients, stock, is_active)
             VALUES (:name, :slug, :category, :price, :mrp, :rating, :image, :short_desc, :description, :ingredients, :stock, :is_active)'
        );
        $stmt->execute([
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':category' => $data['category'],
            ':price' => $data['price'],
            ':mrp' => $data['mrp'],
            ':rating' => $data['rating'] ?? 4.5,
            ':image' => $data['image'],
            ':short_desc' => $data['short_desc'] ?? '',
            ':description' => $data['description'] ?? '',
            ':ingredients' => json_encode($data['ingredients'] ?? []),
            ':stock' => $data['stock'] ?? 50,
            ':is_active' => $data['is_active'] ?? 1,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE products SET name = :name, slug = :slug, category = :category, price = :price, mrp = :mrp,
             image = :image, short_desc = :short_desc, description = :description, ingredients = :ingredients,
             stock = :stock, is_active = :is_active WHERE id = :id'
        );
        $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':category' => $data['category'],
            ':price' => $data['price'],
            ':mrp' => $data['mrp'],
            ':image' => $data['image'],
            ':short_desc' => $data['short_desc'] ?? '',
            ':description' => $data['description'] ?? '',
            ':ingredients' => json_encode($data['ingredients'] ?? []),
            ':stock' => $data['stock'] ?? 50,
            ':is_active' => $data['is_active'] ?? 1,
        ]);
    }

    public static function delete(int $id): void
    {
        Database::connection()->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    }

    public static function decrementStock(int $id, int $qty): void
    {
        Database::connection()->prepare('UPDATE products SET stock = MAX(0, stock - ?) WHERE id = ?')->execute([$qty, $id]);
    }

    public static function countAll(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) c FROM products')->fetch()['c'];
    }

    public static function slugExists(string $slug, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $stmt = Database::connection()->prepare('SELECT COUNT(*) c FROM products WHERE slug = ? AND id != ?');
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = Database::connection()->prepare('SELECT COUNT(*) c FROM products WHERE slug = ?');
            $stmt->execute([$slug]);
        }
        return (int) $stmt->fetch()['c'] > 0;
    }
}
