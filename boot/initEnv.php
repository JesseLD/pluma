<?php

$envPath = __DIR__ . '/../.env';
$examplePath = __DIR__ . '/../.env.example';

if (!file_exists($envPath)) {
    if (file_exists($examplePath)) {
        copy($examplePath, $envPath);
        echo "⚠️  Arquivo .env não encontrado. Criado a partir de .env.example\n";
    } else {
        die("❌ Nenhum arquivo .env ou .env.example encontrado.\n");
    }
}
