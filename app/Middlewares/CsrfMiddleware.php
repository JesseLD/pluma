<?php

namespace App\Middlewares;

use Core\CSRF;
use Core\Exceptions;
use Core\Response;

class CsrfMiddleware
{
  public function handle()
  {
    $method = $_SERVER['REQUEST_METHOD'];

    if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
      $token = $_POST['_csrf']
        ?? $_GET['_csrf']
       // ?? $_SERVER['HTTP_X_CSRF_TOKEN'] // API Disabled
        ?? '';

      if (!CSRF::verifyToken($token)) {
        Response::exception(Exceptions::CSRF_TOKEN_MISMATCH);
      }
    }
  }
}
