<?php

declare(strict_types=1);

namespace App\Routing;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $uri, callable $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, callable $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(string $uri, string $method): string
    {


        $method = strtoupper($method);

        if (!isset($this->routes[$method][$uri])) {
            throw new \RuntimeException("Route not found: [$method] $uri");
        }

        $action = $this->routes[$method][$uri];

        $result = $action();

        if (is_string($result)) {
            return $result;
        }

        return '';
    }
}