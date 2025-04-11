<?php

/**
 * Application configuration file.
 *
 * This file loads values from the .env file and organizes them
 * into a structured array for easy access throughout the application.
 */


return [
    'app' => [
        // Application host, used for generating full URLs
        'host' => get_env('APP_HOST', 'localhost'),

        // Port number for local development or internal reference
        'port' => get_env('APP_PORT', 8000),

        // The name of the application (for display, logs, etc.)
        'name' => get_env('APP_NAME', 'Pluma'),

        // Debug mode - if true, shows detailed error messages
        'debug' => get_env('APP_DEBUG', false) === 'true',

        // API key used for validating protected API routes
        'key' => get_env('API_KEY'),
    ],

    'db' => [
        // Supported: mysql, pgsql, sqlite
        'driver' => get_env('DB_DRIVER', 'mysql'),

        // Database host (IP or domain)
        'host' => get_env('DB_HOST', 'localhost'),

        // Port used by the database server
        'port' => get_env('DB_PORT', 3306),

        // Name of the database
        'name' => get_env('DB_NAME', 'pluma'),

        // Username for database access
        'user' => get_env('DB_USER', 'root'),

        // Password for the database user
        'pass' => get_env('DB_PASS', ''),
    ],
];