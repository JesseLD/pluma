<?php

namespace App\Middlewares;

use Core\Exceptions;
use Core\Response;

/**
 * ApiKeyMiddleware ensures that an API request includes a valid API key.
 * It checks either the query string or the X-API-KEY HTTP header.
 *
 * Must be registered in the router using:
 * $router->group('ApiKeyMiddleware', function (...) { ... });
 */
class ApiKeyMiddleware
{

  protected static string $header = "Authorization";
    /**
     * Handles the API key check.
     *
     * If the provided key does not match the expected one (from .env),
     * a 401 Unauthorized error is returned as JSON.
     *
     * Expected header:
     *   Authorization
     *
     * @return void
     */
    public function handle(): void
    {
        // Try to get the API key from  header
        $providedKey = $_SERVER[Self::$header] ?? null;

        // Load the expected API key from environment
        $validKey = get_env('API_KEY', '');

        // Reject request if key is missing or incorrect
        if ($providedKey !== $validKey) {
            Response::exception(Exceptions::UNAUTHORIZED);
        }
    }
}
