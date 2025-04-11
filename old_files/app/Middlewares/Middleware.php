<?php

namespace App\Middlewares;

use Core\Request;

/**
 * Base middleware class that all middlewares should extend.
 * Provides a standard method signature.
 */
abstract class Middleware
{
  /**
   * Handles the middleware logic.
   * Must be implemented by subclasses.
   *
   * @param mixed $args Optional arguments from route group (e.g. role name)
   * @return void
   */
  abstract public function handle($args = null): void;
}
