<?php

require __DIR__ . '/vendor/autoload.php';

use Core\Database;

$pdo = Database::connection();
$seeds = glob(__DIR__ . '/database/seeds/*.php');

foreach ($seeds as $seedFile) {
    $name = basename($seedFile);

    require_once $seedFile;
    $class = pathinfo($name, PATHINFO_FILENAME);
    if (!class_exists($class)) {
        echo "⚠️  Classe $class não encontrada\n";
        continue;
    }

    echo "🌱 Executando seeder $class...\n";
    (new $class())->run();
}

echo "✅ Todos os seeders executados.\n";
