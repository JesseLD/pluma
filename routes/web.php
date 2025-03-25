<?php

use App\Controllers\HomeController;
use App\Controllers\DashboardController;
use App\Controllers\Auth\LoginController;

// Public routes
$router->get('/', function () {
  redirect('/welcome');
});
$router->get('/welcome', [HomeController::class, 'index']);


$router->get('/login', [LoginController::class, 'index']);

// Middleware grouping example with AuthMiddleware
$router->group('AuthMiddleware', function ($router) {
  $router->get('/home', [DashboardController::class, 'index']);
});
