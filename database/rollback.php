<?php

require __DIR__ . '/vendor/autoload.php';

use Core\Database;

$pdo = Database::connection();

$pdo->exec("CREATE TABLE IF NOT EXISTS migrations (id INTEGER PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255), run_at DATETIME)");

$last = $pdo->query("SELECT * FROM migrations ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

if (!$last) {
    echo "⚠️  Nenhuma migration para desfazer.\n";
    exit;
}

$migrationFile = __DIR__ . '/database/migrations/' . $last['name'];
$className = pathinfo($last['name'], PATHINFO_FILENAME);

if (!file_exists($migrationFile)) {
    echo "❌ Arquivo {$last['name']} não encontrado.\n";
    exit;
}

require_once $migrationFile;

if (!class_exists($className)) {
    echo "❌ Classe $className não encontrada na migration.\n";
    exit;
}

$migration = new $className();

if (!method_exists($migration, 'down')) {
    echo "⚠️  A migration {$last['name']} não possui método down().\n";
    exit;
}

echo "↩️  Desfazendo migration: {$last['name']}...\n";
$migration->down();

$stmt = $pdo->prepare("DELETE FROM migrations WHERE id = ?");
$stmt->execute([$last['id']]);

echo "✅ Migration desfeita com sucesso.\n";
