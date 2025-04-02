<?php

namespace App\Middlewares;

use Core\Request;
use Core\CSRF;

/**
 * Middleware that validates CSRF tokens on unsafe HTTP methods.
 * Only applies to web routes using POST, PUT, PATCH, or DELETE.
 */
class VerifyCsrfToken extends Middleware
{
  /**
   * Handle the incoming request.
   *
   * @param mixed $args Optional args passed from route group (not used here)
   * @return void
   */
  public function handle($args = null): void
  {
    if (in_array(Request::method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
      CSRF::validate();
    }
  }
}
