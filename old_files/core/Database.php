<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Provides a singleton database connection using PDO.
 * Supports MySQL, SQLite, and PostgreSQL based on environment config.
 */
class Database
{
    /**
     * Shared PDO instance.
     *
     * @var PDO|null
     */
    protected static ?PDO $pdo = null;

    /**
     * Returns an active PDO connection. Reuses existing if already connected.
     *
     * @return PDO
     *
     * @throws PDOException
     *
     * Example:
     * $db = Database::connection();
     */
    public static function connection(): PDO
    {
        if (self::$pdo) {
            return self::$pdo;
        }

        $driver = get_env('DB_DRIVER', 'mysql');

        try {
            switch ($driver) {
                case 'sqlite':
                    $dsn = "sqlite:" . get_env('DB_NAME', 'database.sqlite');
                    self::$pdo = new PDO($dsn);
                    break;

                case 'pgsql':
                    $dsn = sprintf(
                        "pgsql:host=%s;port=%s;dbname=%s",
                        get_env('DB_HOST', 'localhost'),
                        get_env('DB_PORT', '5432'),
                        get_env('DB_NAME', 'pluma')
                    );
                    self::$pdo = new PDO($dsn, get_env('DB_USER'), get_env('DB_PASS'));
                    break;

                default: // MySQL
                    $dsn = sprintf(
                        "mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4",
                        get_env('DB_HOST', 'localhost'),
                        get_env('DB_PORT', '3306'),
                        get_env('DB_NAME', 'pluma')
                    );
                    self::$pdo = new PDO($dsn, get_env('DB_USER'), get_env('DB_PASS'));
            }

            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$pdo;

        } catch (PDOException $e) {
            // Return a standardized JSON error and exit
            Response::json("Database Error" . $e->getMessage(), [], 500);
            exit; // Prevent "not all paths return a value"
        }
    }
}
