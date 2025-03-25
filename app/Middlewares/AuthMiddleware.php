<?php

namespace App\Middlewares;

class AuthMiddleware
{
  public function handle()
  {
    session_start();
    if (!isset($_SESSION['user'])) {
      header('Location: /login');
      exit;
    }
  }
}
