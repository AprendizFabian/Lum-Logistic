<?php

namespace App;

class Router
{
    protected array $routes = [];

    public function addRoute(string $method, string $uri, array $action): void
    {
        $this->routes[$method][$uri] = $action;
    }
        public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function get(string $uri, array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function dispatch(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $uri = strtok($uri, '?');

        if ($uri === '') {
            $uri = '/';
        }

        $action = $this->routes[$method][$uri] ?? null;

        if ($action) {
            if (is_array($action) && count($action) === 2) {
                $controllerClass = $action[0];
                $methodName = $action[1];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $methodName)) {
                        $controller->$methodName();
                        return;
                    }
                }
            }
        }

        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        echo "<p>La página que buscas no existe.</p>";
        
    }
}