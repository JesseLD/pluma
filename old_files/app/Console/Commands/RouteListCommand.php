<?php

namespace App\Console\Commands;

use Core\Router;

/**
 * Lists all registered application routes in a formatted table.
 *
 * Usage:
 * php pluma route:list
 *
 * This command loads all routes from the web and API files
 * and prints method, URI, name, and action in a readable format.
 */
class RouteListCommand
{
    /**
     * Executes the command.
     *
     * @param array $args Not used, but required for compatibility.
     */
    public function handle(array $args = [])
    {
        $router = new Router();

        // Load route definitions
        require_once __DIR__ . '/../../../routes/web.php';
        require_once __DIR__ . '/../../../routes/api.php';

        $routes = $router->getRoutes();

        // Header
        echo str_pad('Method', 10) .
             str_pad('URI', 30) .
             str_pad('Name', 30) .
             "Action\n";

        echo str_repeat('-', 100) . "\n";

        // Each route
        foreach ($routes as $route) {
            $method = $route['method'];
            $uri    = '/' . $route['uri'];
            $name   = $route['name'] ?? '-';
            $action = is_array($route['action'])
                ? $route['action'][0] . '@' . $route['action'][1]
                : 'Closure';

            echo str_pad($method, 10) .
                 str_pad($uri, 30) .
                 str_pad($name, 30) .
                 $action . "\n";
        }
    }
}
