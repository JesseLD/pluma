<?php

// ==========================================
// File: pluma/Database/Model.php
// Description: Lightweight base model for database access
// ==========================================

namespace Pluma\Database;

use PDO;
use Pluma\Support\DB;

abstract class Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static string $table;

    /**
     * Model attributes.
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * Construct the model with optional data.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->attributes = $data;
    }

    /**
     * Dynamically get an attribute.
     *
     * @param string $key
     * @return mixed|null
     */
    public function __get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @return static|null
     */
    public static function find(int $id): ?static
    {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new static($row) : null;
    }

    /**
     * Retrieve all records.
     *
     * @return array
     */
    public static function all(): array
    {
        $pdo = DB::pdo();
        $stmt = $pdo->query("SELECT * FROM " . static::$table);
        return array_map(fn($row) => new static($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
