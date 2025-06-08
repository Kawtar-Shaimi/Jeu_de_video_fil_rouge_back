<?php

namespace Core\Router;
use Core\Contracts\ResourceController;
use Core\Decorators\Description;
use Doctrine\Inflector\InflectorFactory;
use ReflectionClass;
use ReflectionMethod;
use Core\Decorators\Route;

class RoutesAnalyzier{
    private RouterContainer $routerContainer;
    private $inflector;

    public function __construct(RouterContainer $routerContainer){
        $this->routerContainer = $routerContainer;
        $this->inflector = InflectorFactory::create()->build();
    }

    public function analyzeControllers()
    {
        $controllers = glob(__DIR__ . '/../../App/Controllers/*.php');

        foreach ($controllers as $file) {
            $className = 'App\\Controllers\\' . basename($file, '.php');

            if (!class_exists($className)) continue;

            $reflection = new ReflectionClass($className);

            $classAttributes = $reflection->getAttributes(Route::class);
            $classPrefix = '';
            $descriptions = [];

            if (count($classAttributes) > 0) {
                $classRoute = $classAttributes[0]->newInstance();
                $classPrefix = $classRoute?->getPath() ?? '';
                if (!str_ends_with($classPrefix, '/')) {
                    $classPrefix .= '/';
                }

                foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                    foreach ($method->getAttributes(Description::class) as $descriptionAttr) {
                        $descriptionInstance = $descriptionAttr->newInstance();
                        $descriptions[] = [
                            'method' => $method->name,
                            'description' => $descriptionInstance->getText()
                        ];
                    }
                    
                    foreach ($method->getAttributes(Route::class) as $attribute) {
                        $route = $attribute->newInstance();
                        $fullPath = rtrim($classPrefix . $route->getPath(), '/');
                        $methodType = $route->getMethod();

                        $this->routerContainer->set($fullPath, $methodType, $reflection, $method->name, $this->findDescriptionByMethodName($descriptions,$method->name));
                    }
                }
            }

            $shortName = $reflection->getShortName();
            if (!str_ends_with($shortName, 'Controller')) continue;

            $entity = substr($shortName, 0, -10);
            $plural = strtolower($this->inflector->pluralize($entity));

            if (strtolower($plural) !== strtolower($plural)) {
                continue;
            }

            if (! $reflection->implementsInterface(ResourceController::class)) {
                continue;
            }

            $basePath = $classPrefix. $plural;

            $this->routerContainer->set($basePath,         'GET',    $reflection, 'index', $this->findDescriptionByMethodName($descriptions,'index'));
            $this->routerContainer->set($basePath . '/{id}','GET',    $reflection, 'show', $this->findDescriptionByMethodName($descriptions,'show'));
            $this->routerContainer->set($basePath,         'POST',   $reflection, 'store', $this->findDescriptionByMethodName($descriptions,'store'));
            $this->routerContainer->set($basePath . '/{id}','PUT',    $reflection, 'update', $this->findDescriptionByMethodName($descriptions,'update'));
            $this->routerContainer->set($basePath . '/{id}','PATCH',  $reflection, 'update', $this->findDescriptionByMethodName($descriptions,'update'));
            $this->routerContainer->set($basePath . '/{id}','DELETE', $reflection, 'destroy', $this->findDescriptionByMethodName($descriptions,'destroy'));
        }
    }

    private function findDescriptionByMethodName(array $descriptions, string $methodName): ?string {
        foreach ($descriptions as $desc) {
            if ($desc['method'] === $methodName) {
                return $desc['description'];
            }
        }
        return null;
    }

}