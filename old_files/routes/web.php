<?php

use App\Controllers\HomeController;
use App\Controllers\Auth\LoginController;
use Core\Router;


// PUBLIC ROUTES (csrf, no authentication required)
$router->group('ThrottleMiddleware:welcome,2,10', function ($router) {

  $router->get('/', function () {
    redirect('/welcome');
  });

  $router->get('/welcome', [HomeController::class, 'index']);
  // $router->group('VerifyCsrfToken', function ($router) {
  // });
  // $router->get('/welcome', [HomeController::class, 'index']);
  $router->get('/login', [LoginController::class, 'index']);


  // AUTHENTICATED ROUTES (csrf, authentication required)
  $router->group('AuthMiddleware', function ($router) {
    // $router->get('/home', [DashboardController::class, 'index']);
  });
});







