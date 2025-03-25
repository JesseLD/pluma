<?php

namespace App\Middlewares;

use Core\Auth;
use Core\Response;

/**
 * AuthMiddleware ensures that the user is authenticated before allowing access.
 * If the user is not logged in, they will be redirected to the login page.
 *
 * Usage:
 * $router->group('AuthMiddleware', function ($router) {
 *     $router->get('/dashboard', [DashboardController::class, 'index']);
 * });
 */
class AuthMiddleware
{
    /**
     * Checks if the user is authenticated.
     * If not, redirects to the login route.
     */
    public function handle(): void
    {
        if (!Auth::check()) {
            Response::redirect('/login');
        }
    }
}
