<?php

/**
 * This script undoes the last executed migration.
 * It looks for the most recent migration in the 'migrations' table,
 * loads the corresponding class, and calls the down() method.
 */

require __DIR__ . '/vendor/autoload.php';

use Core\Database;

$pdo = Database::connection();

// Ensure the migrations table exists
$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255),
        run_at DATETIME
    )
");

// Get the last applied migration
$last = $pdo->query("SELECT * FROM migrations ORDER BY id DESC LIMIT 1")
    ->fetch(PDO::FETCH_ASSOC);

if (!$last) {
    echo "⚠️  No migrations to rollback.\n";
    exit;
}

$migrationFile = __DIR__ . '/database/migrations/' . $last['name'];
$className = pathinfo($last['name'], PATHINFO_FILENAME);

// Ensure the migration file exists
if (!file_exists($migrationFile)) {
    echo "❌ Migration file {$last['name']} not found.\n";
    exit;
}

require_once $migrationFile;

// Ensure the migration class exists
if (!class_exists($className)) {
    echo "❌ Migration class $className not found.\n";
    exit;
}

$migration = new $className();

// Ensure the migration has a 'down' method
if (!method_exists($migration, 'down')) {
    echo "⚠️  Migration {$last['name']} does not define a down() method.\n";
    exit;
}

// Rollback the migration
echo "↩️  Rolling back migration: {$last['name']}...\n";
$migration->down();

// Remove the migration record from the table
$stmt = $pdo->prepare("DELETE FROM migrations WHERE id = ?");
$stmt->execute([$last['id']]);

echo "✅ Migration successfully rolled back.\n";
