<?php

use App\Controllers\Api\UserController;
use Core\Router;


// API ROUTES (no csrf, no authentication required, api key required)
$router->group('ApiKeyMiddleware', function ($router) {
  $router->get('/api/users', [UserController::class, 'index']);
});