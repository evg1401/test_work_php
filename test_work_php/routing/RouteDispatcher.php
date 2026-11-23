<?php

declare(strict_types=1);

namespace App\Routing;

use App\Shared\Http\ViewModel\ErrorViewModel;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Http\Exception as HttpException;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\UrlGeneratorInterface;
use Smarty\Smarty;
use Throwable;

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
        $container->addShared(UrlGeneratorInterface::class, $router);

        $routes = require __DIR__ . '/web.php';
        (new RouteRegistrar($router))->register($routes);

        try {
            $response = $router->dispatch($request);
        } catch (HttpException $exception) {
            $statusCode = $exception->getStatusCode();

            if ($statusCode >= 500) {
                error_log((string) $exception);
            }

            $response = (new Response())
                ->withStatus($statusCode)
                ->withHeader('Content-Type', 'text/html; charset=utf-8');

            $smarty = $container->get(Smarty::class);
            $smarty->assign('view', new ErrorViewModel(
                title: $response->getReasonPhrase(),
                statusCode: $statusCode,
                message: $exception->getMessage(),
            ));

            $response->getBody()->write($smarty->fetch('errors/error.tpl'));
        } catch (Throwable $exception) {
            error_log((string) $exception);

            $statusCode = 500;
            $response = (new Response())
                ->withStatus($statusCode)
                ->withHeader('Content-Type', 'text/html; charset=utf-8');

            $smarty = $container->get(Smarty::class);
            $smarty->assign('view', new ErrorViewModel(
                title: $response->getReasonPhrase(),
                statusCode: $statusCode,
                message: 'Внутренняя ошибка сервера.',
            ));

            $response->getBody()->write($smarty->fetch('errors/error.tpl'));
        }

        $response = $response->withHeader(
            'Content-Security-Policy',
            "default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; script-src 'self'; style-src 'self' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self'",
        );

        (new SapiEmitter())->emit($response);
    }
}
