<?php

namespace App\Models;

use PDO;
use Core\Database;

/**
 * BaseModel provides shared ORM-like functionality for all models.
 * Handles attributes, find/save/delete operations, and uses QueryBuilder for where().
 */
abstract class BaseModel
{
    /**
     * The name of the database table associated with the model.
     *
     * Must be defined in each child model class:
     * protected static string $table = 'users';
     */
    protected static string $table;

    /**
     * The name of the primary key column (default: 'id').
     */
    protected static string $primaryKey = 'id';

    /**
     * Holds the current attribute values of the model.
     */
    protected array $attributes = [];

    /**
     * Stores the original values (before changes).
     */
    protected array $original = [];

    /**
     * Cached PDO connection (shared).
     */
    protected static ?PDO $connection = null;

    /**
     * Constructs the model instance and fills its attributes.
     *
     * @param array $attributes Initial values for the model.
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Returns the table name defined by the model.
     */
    public static function table(): string
    {
        return static::$table;
    }

    /**
     * Fills the model with an associative array of attributes.
     *
     * @param array $attributes
     */
    protected function fill(array $attributes)
    {
        $this->attributes = $attributes;
        $this->original = $attributes;
    }

    /**
     * Gets an attribute value (e.g. $user->email).
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Sets an attribute value (e.g. $user->email = 'test@...').
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Retrieves or initializes the PDO connection.
     */
    protected static function getConnection(): PDO
    {
        if (!static::$connection) {
            static::$connection = Database::connection();
        }
        return static::$connection;
    }

    /**
     * Retrieves all records from the model's table.
     *
     * @return static[] Array of model instances
     *
     * Example:
     * $users = User::all();
     */
    public static function all(): array
    {
        $stmt = static::getConnection()->query("SELECT * FROM " . static::$table);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new static($row), $results);
    }

    /**
     * Finds a single record by its primary key.
     *
     * @param mixed $id
     * @return static|null
     *
     * Example:
     * $user = User::find(1);
     */
    public static function find($id): ?static
    {
        $stmt = static::getConnection()->prepare(
            "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new static($row) : null;
    }

    /**
     * Begins a query with a WHERE condition.
     *
     * @param string $column Column name
     * @param mixed $value Value to match
     * @return QueryBuilder
     *
     * Example:
     * $user = User::where('email', 'test@example.com')->first();
     */
    public static function where(string $column, $value): QueryBuilder
    {
        return (new QueryBuilder(static::class))->where($column, $value);
    }

    /**
     * Saves the current model to the database (insert or update).
     *
     * @return bool
     *
     * Example:
     * $user = new User(['name' => 'John']);
     * $user->save();
     */
    public function save(): bool
    {
        $conn = static::getConnection();
        $keys = array_keys($this->attributes);
        $columns = implode(', ', $keys);
        $placeholders = implode(', ', array_map(fn($k) => ":$k", $keys));

        if (!isset($this->attributes[static::$primaryKey])) {
            // Insert
            $sql = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";
        } else {
            // Update
            $updates = implode(', ', array_map(fn($k) => "$k = :$k", $keys));
            $sql = "UPDATE " . static::$table . " SET $updates WHERE " . static::$primaryKey . " = :id";
            $this->attributes['id'] = $this->attributes[static::$primaryKey];
        }

        $stmt = $conn->prepare($sql);
        return $stmt->execute($this->attributes);
    }

    /**
     * Deletes the current model record from the database.
     *
     * @return bool True on success, false if no primary key set.
     *
     * Example:
     * $user = User::find(1);
     * $user->delete();
     */
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
