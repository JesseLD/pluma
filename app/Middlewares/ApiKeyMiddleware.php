<?php

namespace App\Middlewares;

use Core\Exceptions;
use Core\Response;

class ApiKeyMiddleware
{
  public function handle()
  {
    $providedKey = $_GET['api_key'] ?? $_SERVER['HTTP_X_API_KEY'] ?? null;
    $validKey = get_env('API_KEY','');

    if ($providedKey !== $validKey) {
      Response::exception(Exceptions::UNAUTHORIZED);
    }
  }
}
