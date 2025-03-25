<?php

namespace App\Models;

use PDO;
use Core\Database;

abstract class BaseModel
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    protected array $attributes = [];
    protected array $original = [];

    protected static ?PDO $connection = null;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public static function table(): string
    {
        return static::$table;
    }

    protected function fill(array $attributes)
    {
        $this->attributes = $attributes;
        $this->original = $attributes;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    protected static function getConnection(): PDO
    {
        if (!static::$connection) {
            static::$connection = Database::connection();
        }
        return static::$connection;
    }

    public static function all(): array
    {
        $stmt = static::getConnection()->query("SELECT * FROM " . static::$table);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new static($row), $results);
    }

    public static function find($id): ?static
    {
        $stmt = static::getConnection()->prepare("SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new static($row) : null;
    }

    public static function where(string $column, $value): QueryBuilder
    {
        return (new QueryBuilder(static::class))->where($column, $value);
    }

    public function save(): bool
    {
        $conn = static::getConnection();
        $keys = array_keys($this->attributes);
        $columns = implode(', ', $keys);
        $placeholders = implode(', ', array_map(fn($k) => ":$k", $keys));

        if (!isset($this->attributes[static::$primaryKey])) {
            $sql = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";
        } else {
            $updates = implode(', ', array_map(fn($k) => "$k = :$k", $keys));
            $sql = "UPDATE " . static::$table . " SET $updates WHERE " . static::$primaryKey . " = :id";
            $this->attributes['id'] = $this->attributes[static::$primaryKey];
        }

        $stmt = $conn->prepare($sql);
        return $stmt->execute($this->attributes);
    }

    public function delete(): bool
    {
        if (!isset($this->attributes[static::$primaryKey])) {
            return false;
        }

        $sql = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id";
        $stmt = static::getConnection()->prepare($sql);
        return $stmt->execute(['id' => $this->attributes[static::$primaryKey]]);
    }
}
