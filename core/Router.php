<?php

namespace Core;

/**
 * Handles registration and dispatching of application routes.
 * Supports GET/POST methods, controller actions, closures, parameters, and middleware.
 */
class Router
{
    /**
     * Registered route definitions.
     *
     * @var array
     */
    protected array $routes = [];

    /**
     * Middleware currently applied to grouped routes.
     *
     * @var string|null
     */
    protected ?string $currentMiddleware = null;

    /**
     * Registers a GET route.
     *
     * @param string $uri The URI pattern (e.g. '/home' or '/user/{id}')
     * @param callable|array $action A closure or [Controller::class, 'method']
     */
    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * Registers a POST route.
     *
     * @param string $uri The URI pattern
     * @param callable|array $action The route handler
     */
    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * Groups multiple routes under a middleware.
     *
     * @param string $middleware The middleware name (e.g. 'AuthMiddleware:admin')
     * @param callable $callback Function that receives $router and registers routes
     *
     * Example:
     * $router->group('AuthMiddleware', function($router) {
     *     $router->get('/dashboard', ...);
     * });
     */
    public function group($middleware, $callback)
    {
        $previous = $this->currentMiddleware;
        $this->currentMiddleware = $middleware;
        $callback($this);
        $this->currentMiddleware = $previous;
    }

    /**
     * Registers a new route internally with regex pattern and middleware.
     *
     * @param string $method HTTP method (GET or POST)
     * @param string $uri URI pattern
     * @param mixed $action Closure or controller action
     * @param string|null $name Optional route name
     */
    protected function addRoute($method, $uri, $action, $name = null)
    {
        $uri = trim($uri, '/');

        // Convert {param} to regex (e.g. /user/{id} => /user/([^/]+))
        $pattern = preg_replace('/\{([\w]+)\}/', '([^/]+)', $uri);
        $pattern = "#^{$pattern}$#";

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'pattern' => $pattern,
            'action' => $action,
            'middleware' => $this->currentMiddleware,
            'name' => $name
        ];
    }

    /**
     * Sets a name for the last registered route.
     *
     * @param string $name
     *
     * Example:
     * $router->get('/profile', ...);
     * $router->name('profile');
     */
    public function name($name)
    {
        $lastIndex = count($this->routes) - 1;
        $this->routes[$lastIndex]['name'] = $name;
    }

    /**
     * Resolves the current request URI and method and executes the matching route.
     * Applies middleware, extracts parameters, and calls the controller or closure.
     */
    public function dispatch()
    {
        $requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) continue;

            // Match URI pattern
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                array_shift($matches); // remove full match

                // Middleware support (optional args via "Middleware:role")
                if ($route['middleware']) {
                    $middlewareParts = explode(':', $route['middleware']);
                    $middlewareClass = "App\\Middlewares\\" . $middlewareParts[0];
                    $middlewareArgs = $middlewareParts[1] ?? null;

                    if (class_exists($middlewareClass)) {
                        $middleware = new $middlewareClass();
                        $middleware->handle($middlewareArgs);
                    }
                }

                // Closure-based route
                if (is_callable($route['action'])) {
                    echo call_user_func_array($route['action'], $matches);
                    return;
                }

                // Controller-based route [Controller::class, 'method']
                [$controller, $method] = $route['action'];
                $controllerInstance = new $controller();
                echo call_user_func_array([$controllerInstance, $method], $matches);
                return;
            }
        }

        // No matching route
        Response::exception(Exceptions::NOT_FOUND);
    }

    /**
     * Returns all registered routes (useful for debug or dev tools).
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
