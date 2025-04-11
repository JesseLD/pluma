<?php


// ==========================================
// File: pluma/Support/DB.php
// Description: Handles creation of the PDO instance for database access
// ==========================================

namespace Pluma\Support;

use PDO;

class DB
{
    /**
     * The shared PDO instance.
     *
     * @var PDO|null
     */
    protected static ?PDO $pdo = null;

    /**
     * Initialize the PDO connection.
     *
     * @param array $config
     */
    public static function init(array $config): void
    {
        static::$pdo = new PDO(
            $config['dsn'],
            $config['username'] ?? null,
            $config['password'] ?? null,
            $config['options'] ?? []
        );
        static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Get the PDO instance.
     *
     * @return PDO
     */
    public static function pdo(): PDO
    {
        if (!static::$pdo) {
            throw new \RuntimeException("PDO connection is not initialized.");
        }

        return static::$pdo;
    }
}