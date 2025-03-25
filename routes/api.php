<?php

use App\Controllers\Api\UserController;

// Rota protegida por chave de API
$router->group('ApiKeyMiddleware', function ($router) {
  $router->get('/api/users', [UserController::class, 'index']);
});
