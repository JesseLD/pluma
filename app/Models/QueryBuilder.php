<?php

namespace App\Models;

use PDO;
use Core\Database;

class QueryBuilder
{
    protected PDO $connection;
    protected string $modelClass;
    protected string $table;

    protected array $wheres = [];
    protected array $bindings = [];

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->connection = Database::connection();
        $this->table = $modelClass::table();
    }

    public function where(string $column, $value): static
    {
        $this->wheres[] = "$column = ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function get(): array
    {
        $sql = "SELECT * FROM " . $this->table;

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->bindings);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new $this->modelClass($row), $rows);
    }

    public function first(): ?object
    {
        $sql = "SELECT * FROM " . $this->table;

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $sql .= " LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->bindings);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new $this->modelClass($row) : null;
    }
}
