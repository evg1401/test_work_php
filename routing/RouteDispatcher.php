<?php

declare(strict_types=1);

namespace App\Routing;

use App\Shared\Http\ViewModel\ErrorViewModel;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Smarty\Smarty;

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

        $container = require dirname(__DIR__) . '/bootstrap/container.php';
        $strategy = new ApplicationStrategy();
        $strategy->setContainer($container);

        $router = new Router();
        $router->setStrategy($strategy);

        $routes = require __DIR__ . '/web.php';
        (new RouteRegistrar($router))->register($routes);

        try {
            $response = $router->dispatch($request);
        } catch (NotFoundException) {
            $smarty = $container->get(Smarty::class);
            $smarty->assign('view', new ErrorViewModel(
                title: 'Страница не найдена',
                statusCode: 404,
                message: 'Запрошенный ресурс не найден.',
            ));

            $response = (new Response())
                ->withStatus(404)
                ->withHeader('Content-Type', 'text/html; charset=utf-8');

            $response->getBody()->write($smarty->fetch('errors/error.tpl'));
        }

        (new SapiEmitter())->emit($response);
    }
}
