<?php
namespace App\Core;

use PDO;

class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $dbPath = dirname(__DIR__, 2) . '/database/app.sqlite';
        $isNew = !file_exists($dbPath);

        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('PRAGMA foreign_keys = ON');

        self::$pdo = $pdo;

        if ($isNew) {
            self::migrate($pdo);
            self::seed($pdo);
        } else {
            self::runPendingMigrations($pdo);
        }

        return $pdo;
    }

    private static function migrate(PDO $pdo): void
    {
        $schema = file_get_contents(dirname(__DIR__, 2) . '/database/schema.sql');
        $pdo->exec($schema);
    }

    /**
     * Lets a fresh feature ship without forcing users who already have a
     * database file to delete it — new columns/tables get added in place.
     */
    private static function runPendingMigrations(PDO $pdo): void
    {
        $cols = $pdo->query("PRAGMA table_info(products)")->fetchAll();
        $names = array_column($cols, 'name');
        if (!in_array('stock', $names, true)) {
            $pdo->exec("ALTER TABLE products ADD COLUMN stock INTEGER NOT NULL DEFAULT 50");
        }
    }

    private static function seed(PDO $pdo): void
    {
        require dirname(__DIR__, 2) . '/database/seed.php';
        seed_database($pdo);
    }
}
