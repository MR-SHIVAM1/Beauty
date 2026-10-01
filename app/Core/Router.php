<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, callable $handler): void
    {
        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', rtrim($path, '/'));
        $pattern = $pattern === '' ? '/' : $pattern;
        preg_match_all('#\{([a-zA-Z_]+)\}#', $path, $paramNames);

        $this->routes[] = [
            'method' => $method,
            'pattern' => '#^' . $pattern . '$#',
            'params' => $paramNames[1],
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches);
                $args = array_combine($route['params'], $matches);
                call_user_func($route['handler'], $args);
                return;
            }
        }
        Response::notFound();
    }
}
