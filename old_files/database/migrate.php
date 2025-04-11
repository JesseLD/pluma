<?php

/**
 * This script applies all new migrations that haven't been executed yet.
 * Each migration must define a class with an up() method.
 * Executed migrations are tracked in the `migrations` table.
 */

require __DIR__ . '/vendor/autoload.php';

use Core\Database;

$pdo = Database::connection();

// Create the migration tracking table if it doesn't exist
$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255),
        run_at DATETIME
    )
");

// Get the names of already executed migrations
$executed = $pdo->query("SELECT name FROM migrations")
    ->fetchAll(PDO::FETCH_COLUMN);

// Get all migration files from the migrations directory
$migrations = glob(__DIR__ . '/database/migrations/*.php');

foreach ($migrations as $migrationFile) {
    $name = basename($migrationFile);

    // Skip already executed migrations
    if (in_array($name, $executed)) {
        echo "✔️  $name already executed\n";
        continue;
    }

    require_once $migrationFile;

    // Extract the class name from the file
    $class = pathinfo($name, PATHINFO_FILENAME);

    if (!class_exists($class)) {
        echo "⚠️  Class $class not found in $name\n";
        continue;
    }

    echo "🚀 Running migration: $name...\n";

    // Run the migration's up() method
    (new $class())->up();

    // Record the migration in the database
    $stmt = $pdo->prepare("INSERT INTO migrations (name, run_at) VALUES (?, NOW())");
    $stmt->execute([$name]);
}

echo "✅ All pending migrations executed.\n";
