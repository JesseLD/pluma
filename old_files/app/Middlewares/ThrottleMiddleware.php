<?php

namespace App\Middlewares;

use App\Middlewares\Middleware;
use Core\Exceptions;
use Core\Request;
use Core\Response;
use Core\Throttle;

/**
 * ThrottleMiddleware limits the number of allowed requests
 * per identifier (usually IP) in a given time frame.
 *
 * Example usage in routes:
 * Router::group('ThrottleMiddleware:login,5,60', function ($router) {
 *     $router->post('/login', [LoginController::class, 'authenticate']);
 * });
 *
 * This will allow a maximum of 5 login attempts every 60 seconds per IP.
 */
class ThrottleMiddleware extends Middleware
{
  /**
   * Handles the request rate limiting logic.
   *
   * @param string|null $args Comma-separated string: key,max_attempts,seconds
   *                          e.g. "login,5,60"
   *
   * @throws \Exception if the throttle limit is exceeded
   */
  public function handle($args = null): void
  {
    // Parse arguments: key, max attempts, duration (seconds)
    [$key, $max, $seconds] = explode(',', $args);


    if (!Throttle::check($key, Request::ip(), (int)$max, (int)$seconds)) {
      Response::exception(Exceptions::TOO_MANY_REQUESTS);
    }
  }
}
