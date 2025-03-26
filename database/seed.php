<?php

/**
 * This script loads and runs all seed classes from the /database/seeds directory.
 * Each seeder file must define a class with a run() method.
 */

require __DIR__ . '/vendor/autoload.php';

use Core\Database;

// Establish the database connection
$pdo = Database::connection();

// Load all PHP files from the seeds directory
$seeds = glob(__DIR__ . '/database/seeds/*.php');

foreach ($seeds as $seedFile) {
    $name = basename($seedFile);

    require_once $seedFile;

    // Extract class name from file name (e.g. UserSeeder.php => UserSeeder)
    $class = pathinfo($name, PATHINFO_FILENAME);

    if (!class_exists($class)) {
        echo "⚠️  Class $class not found\n";
        continue;
    }

    echo "🌱 Running seeder: $class...\n";
    (new $class())->run();
}

echo "✅ All seeders completed.\n";
