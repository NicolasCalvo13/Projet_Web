<?php
namespace App\Core;
class Router {
    private array $routes = [];
    public function get(string $path, string $controller, string $action): void {
        $this->routes[] = ['GET', $path, $controller, $action];
    }
    public function post(string $path, string $controller, string $action): void {
        $this->routes[] = ['POST', $path, $controller, $action];
    }
    public function dispatch(string $uri, string $method): void {
        $uri = strtok($uri, '?');
        foreach ($this->routes as [$routeMethod, $path, $ctrl, $action]) {
            $pattern = '#^' . preg_replace('/:([a-z]+)/', '(?P<$1>[^/]+)', $path) . '$#';
            if ($method === $routeMethod && preg_match($pattern, $uri, $matches)) {
                $controller = "App\\Controllers\\$ctrl";
                (new $controller())->$action($matches);
                return;
            }
        }
        http_response_code(404);
        echo "404 - Page non trouvée";
    }
}
