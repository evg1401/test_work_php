<?php

declare(strict_types=1);

namespace App\Routes;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;

final class RouteDispatcher
{
    public function dispatch(): void
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES,
        );

        $router = new Router();
        $routes = require __DIR__ . '/web.php';

        (new RouteRegistrar($router))->register($routes);

        try {
            $response = $router->dispatch($request);
        } catch (NotFoundException) {
            $response = (new Response())
                ->withStatus(404)
                ->withHeader('Content-Type', 'text/plain; charset=utf-8');

            $response->getBody()->write('404 ресурс не найден');
        }

        (new SapiEmitter())->emit($response);
    }
}
