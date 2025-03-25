<?php

namespace App\Console\Commands;

use Core\Router;

class RouteListCommand
{
    public function handle(array $args = [])
    {
        $router = new Router();

        // Carrega as rotas
        require_once __DIR__ . '/../../../routes/web.php';
        require_once __DIR__ . '/../../../routes/api.php';

        $routes = $router->getRoutes();

        echo "Method\tURI\t\t\tName\t\t\tAction\n";
        echo "-------------------------------------------------------------\n";

        foreach ($routes as $route) {
            $method = $route['method'];
            $uri = '/' . $route['uri'];
            $name = $route['name'] ?? '';
            $action = is_array($route['action'])
                ? $route['action'][0] . '@' . $route['action'][1]
                : 'Closure';

            echo "$method\t$uri\t\t$name\t\t$action\n";
        }
    }
}
