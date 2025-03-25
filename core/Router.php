<?php

namespace Core;

class Router
{
  protected $routes = [];
  protected $currentMiddleware = null;

  public function get($uri, $action)
  {
    $this->addRoute('GET', $uri, $action);
  }

  public function getRoutes()
  {
    return $this->routes;
  }


  public function post($uri, $action)
  {
    $this->addRoute('POST', $uri, $action);
  }

  public function group($middleware, $callback)
  {
    $previous = $this->currentMiddleware;
    $this->currentMiddleware = $middleware;
    $callback($this);
    $this->currentMiddleware = $previous;
  }

  protected function addRoute($method, $uri, $action, $name = null)
  {
    $this->routes[] = [
      'method' => $method,
      'uri' => trim($uri, '/'),
      'action' => $action,
      'middleware' => $this->currentMiddleware,
      'name' => $name
    ];
  }

  public function name($name)
  {
    $lastIndex = count($this->routes) - 1;
    $this->routes[$lastIndex]['name'] = $name;
  }


  public function dispatch()
  {
    $requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    foreach ($this->routes as $route) {
      if ($route['method'] === $requestMethod && $route['uri'] === $requestUri) {

        // Middleware
        if ($route['middleware']) {
          $middlewareClass = "App\\Middlewares\\" . $route['middleware'];
          if (class_exists($middlewareClass)) {
            $middleware = new $middlewareClass();
            $middleware->handle();
          }
        }

        // Verifica se é closure
        if (is_callable($route['action'])) {
          echo call_user_func($route['action']);
          return;
        }

        // Controller + método
        [$controller, $method] = $route['action'];
        $controllerInstance = new $controller();
        echo $controllerInstance->$method();
        return;
      }
    }

    Response::exception(Exceptions::NOT_FOUND);
  }
}
