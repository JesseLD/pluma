<?php

use App\Controllers\Api\UserController;

// This is an example of how to use the API key middleware
$router->group('ApiKeyMiddleware', function ($router) {
  $router->get('/api/users', [UserController::class, 'index']);
});
