<?php

namespace App\Routers;

class Router
{
    private array $routes = [];
    private $notFoundFunction;

    public function add(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function setNotFound(callable $handler): void
    {
        $this->notFoundFunction = $handler;
    }

    private function getNotFound(): callable
    {
        return $this->notFoundFunction;
    }

    protected function handlerIsArray(callable|array $handler): bool
    {
        return is_array($handler) && count($handler) === 2 && is_object($handler[0]) && is_string($handler[1]);
    }

    public function dispatch(string $method, string $uri)
    {
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                $handler = $route['handler'];

                if ($this->handlerIsArray($handler)) {
                    return call_user_func([$handler[0], $handler[1]]);
                }

                return call_user_func($handler);
            }
        }

        http_response_code(404);
        return call_user_func($this->getNotFound());
    }
}
