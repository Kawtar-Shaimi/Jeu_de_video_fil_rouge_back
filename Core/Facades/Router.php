<?php

namespace Core\Facades;

use Core\Router\RouterContainer;
use Core\Router\RoutesAnalyzier;
use Core\Request;

class Router
{
    private RouterContainer $routerContainer;
    private RoutesAnalyzier $routesAnalyzier;
    private Request $request;

    public function __construct()
    {
        $this->routerContainer = new RouterContainer();
        $this->routesAnalyzier = new RoutesAnalyzier($this->routerContainer);
        $this->routesAnalyzier->analyzeControllers();
        $this->request = new Request();
    }
    public function dispatch()
    {
        $path = $this->request->relativeUrl();
        $httpMethod = $this->request->getMethod();
        if ($path === '/') {
            $allRoutes = [];
        
            foreach ($this->routerContainer->getRoutes() as $route) {
                $routeInfo = [
                    'method' => strtoupper($route['httpMethod']),
                    'path' => $route['path'],
                ];
                if($route['description']){
                    $routeInfo['description'] = $route['description'] ;
                }
                $allRoutes[] = $routeInfo;
            }
        
            header('Content-Type: application/json');
            echo json_encode($allRoutes, JSON_PRETTY_PRINT);
            exit;
        }
        
        try{
            $route = $this->routerContainer->get($path, $httpMethod);
        }
        catch(\Exception $e){
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
            return false;
        }

        if (!$route) {
            http_response_code(404);
            echo json_encode(['error' => "Route not found for $httpMethod $path"]);
            return false;
        }

        $controller = $route['factory']->newInstance();
        $method = $route['method'];

        if (!method_exists($controller, $method)) {
            http_response_code(405);
            echo json_encode(['error' => "Method '$method' not allowed on controller"]);
            return false;
        }

        call_user_func_array([$controller, $method], $route['params']);
        return true;
    }

}

?>