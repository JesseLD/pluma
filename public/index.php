<?php

/**
 * This is the main entry point of the Pluma Framework.
 * It loads the application, registers routes, and handles the HTTP request.
 */

// Load global helper functions (e.g. asset(), e(), redirect(), etc.)
require_once __DIR__ . '/../core/helpers.php';

// Load bootstrap and environment setup (.env, CSRF, Composer autoload)
require_once __DIR__ . '/../boot/app.php';

use Core\Router;

// Create a new router instance
$router = new Router();

// Register all application routes from routes/web.php
require_once __DIR__ . '/../routes/web.php';

// Dispatch the incoming request and execute the matched route
$router->dispatch();
