<?php

declare(strict_types=1);

namespace App\Routes;

use League\Route\Router;

final class RouteRegistrar
{
    public function __construct(
        private readonly Router $router,
    ) {}

    public function register(array $routes): void
    {
        foreach ($routes as $route) {
            $this->router->map(
                $route['method'],
                $route['path'],
                [$route['controller'], $route['handler']],
            );
        }
    }
}
