<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
  protected static $pdo;

  public static function connection(): PDO
  {
    if (self::$pdo) {
      return self::$pdo;
    }

    $driver = $_ENV['DB_DRIVER'] ?? 'mysql';

    try {
      switch ($driver) {
        case 'sqlite':
          $dsn = "sqlite:" . ($_ENV['DB_NAME'] ?? 'database.sqlite');
          self::$pdo = new PDO($dsn);
          break;

        case 'pgsql':
          $dsn = "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
          self::$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
          break;

        default:
          // mysql
          $dsn = "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
          self::$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
      }

      self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return self::$pdo;
    } catch (PDOException $e) {
      Response::json("Database Connection Error: " . $e->getMessage(), [], $e->getMessage(), 500);
    }
  }
}
