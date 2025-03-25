<?php

/**
 * Bootstrap file to initialize the Pluma framework environment.
 * This file is executed before the app handles requests.
 *
 * Responsibilities:
 * - Load environment variables from .env
 * - Load CSRF protection
 * - Autoload Composer dependencies
 * - Load framework helpers
 * - Initialize core services
 */

// Load environment handling (get_env)
require_once 'init_env.php';

// Load CSRF token generator and validator
require_once 'init_csrf.php';

use Dotenv\Dotenv;

// Autoload all Composer dependencies
require_once __DIR__ . '/../vendor/autoload.php';

// Load Pluma helper functions
require_once __DIR__ . '/../core/helpers.php';

// Load .env variables into $_ENV
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/**
 * Optional: You can load configs, initialize the router,
 * register service providers, or setup error handling here.
 *
 * This file should be required in `public/index.php`
 */
