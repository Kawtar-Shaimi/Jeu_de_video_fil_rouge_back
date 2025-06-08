<?php
namespace Core\Router;

use ReflectionClass;

class RouterContainer {
    private array $routes = []; 

    // Register a route
    public function set(string $path, string $httpMethod, ReflectionClass $factory, string $callableMethod, $description): void
    {
        $httpMethod = strtoupper($httpMethod);
        $path = rtrim($path, '/');

        $this->routes[] = [
            'httpMethod' => $httpMethod,
            'path' => $path,
            'factory' => $factory,
            'method' => $callableMethod,
            'description' => $description
        ];
    }


    public function get(string $currentPath, string $httpMethod): array
    {
        $httpMethod = strtoupper($httpMethod);
        $currentPath = rtrim($currentPath, '/');

        foreach ($this->routes as $route) {
            if($httpMethod === 'OPTIONS'){
                header("Access-Control-Allow-Origin: *");
                header("Access-Control-Allow-Headers: *");
                header("Access-Control-Allow-Methods: *");
                header("HTTP/1.1 200 OK");
                exit();
            }
            if ($route['httpMethod'] !== $httpMethod) {
                continue;
            }
            $params = [];
            if ($this->matchPath($route['path'], $currentPath, $params)) {
                $route['params'] = $params;
                return $route;
            }
        }

        throw new \Exception("No {$httpMethod} method defined for route '{$currentPath}'.");
    }

    public function getRoutes(){
        return $this->routes;
    }

    private function matchPath(string $routePattern, string $uri, array &$params): bool
    {
        $pattern = preg_replace_callback('#\{([^}]+)\}#', function($matches) {
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, $routePattern);

        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $uri, $matches)) {
            $params = [];
            foreach ($matches as $key => $value) {
                if (!is_int($key)) {
                    $params[$key] = $value;
                }
            }
            return true;
        }

        return false;
    }
}
