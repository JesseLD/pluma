<?php


// ==========================================
// File: pluma/Routing/Router.php
// Description: Manages route registration and dispatching
// ==========================================

namespace Pluma\Routing;

class Router
{
    /**
     * All registered routes.
     *
     * @var array
     */
    protected array $routes = [];

    /**
     * Register a new GET route.
     *
     * @param string $uri
     * @param callable $action
     */
    public function get(string $uri, callable $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    /**
     * Dispatch a request based on method and URI.
     *
     * @param string $method
     * @param string $uri
     */
    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);

        if (isset($this->routes[$method][$uri])) {
            call_user_func($this->routes[$method][$uri]);
        } else {
            http_response_code(404);
            echo "Route not found";
        }
    }
}
