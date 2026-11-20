<?php

declare(strict_types=1);

namespace App\Home\Http;

use App\Home\Http\ViewModel\HomeViewModel;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty\Smarty;

final class HomeController
{
    public function __construct(
        private readonly Smarty $smarty,
    ) {}

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $view = new HomeViewModel(
            title: 'Главная',
            intro: 'последние статьи',
            categories: [
                ['id' => 1, 'title' => 'категория 1', 'description' => 'описание'],
                ['id' => 2, 'title' => 'категория 2', 'description' => 'описание'],
            ],
            articles: [
                ['id' => 1, 'title' => 'статья 1', 'description' => 'описание'],
                ['id' => 2, 'title' => 'статья 1', 'description' => 'описание'],
            ],
        );
        $this->smarty->assign('view', $view);

        $response = new Response();
        $response->getBody()->write($this->smarty->fetch('domain/home/index.tpl'));

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
