<?php

require __DIR__ . '/vendor/autoload.php';

use Core\Database;

$pdo = Database::connection();

// Cria tabela de controle de migrations
$pdo->exec("CREATE TABLE IF NOT EXISTS migrations (id INTEGER PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255), run_at DATETIME)");

$executed = $pdo->query("SELECT name FROM migrations")->fetchAll(PDO::FETCH_COLUMN);
$migrations = glob(__DIR__ . '/database/migrations/*.php');

foreach ($migrations as $migrationFile) {
    $name = basename($migrationFile);

    if (in_array($name, $executed)) {
        echo "✔️  $name já executada\n";
        continue;
    }

    require_once $migrationFile;
    $class = pathinfo($name, PATHINFO_FILENAME);
    if (!class_exists($class)) {
        echo "⚠️  Classe $class não encontrada em $name\n";
        continue;
    }

    echo "🚀 Executando $name...\n";
    (new $class())->up();

    $stmt = $pdo->prepare("INSERT INTO migrations (name, run_at) VALUES (?, NOW())");
    $stmt->execute([$name]);
}

echo "✅ Migrations completas.\n";
