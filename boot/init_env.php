<?php

/**
 * Ensures that a .env file exists in the project root.
 * If not found, attempts to copy from .env.example.
 * Terminates with an error message if neither exists.
 */

$envPath = __DIR__ . '/../.env';
$examplePath = __DIR__ . '/../.env.example';

if (!file_exists($envPath)) {
    if (file_exists($examplePath)) {
        copy($examplePath, $envPath);
        echo "⚠️  .env file not found. Created from .env.example.\n";
    } else {
        die("❌ No .env or .env.example file found. Cannot continue.\n");
    }
}
