<?php

namespace Core\Decorators;

use Attribute;
use Core\Router\RouteMethod;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    public function __construct(
        private string $path,
        private string $method = RouteMethod::GET
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }
    public function getMethod(): string
    {
        return $this->method;
    }
}
