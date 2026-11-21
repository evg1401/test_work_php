<?php

declare(strict_types=1);

namespace App\Home\Http;

use App\Category\Application\CategoryService;
use App\Home\Http\ViewModel\HomeViewModel;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty\Smarty;

final class HomeController
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly Smarty $smarty,
    ) {}

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $view = new HomeViewModel(
            title: 'Главная',
            categories: $this->categoryService->findWithLatestArticles(),
        );
        $this->smarty->assign('view', $view);

        $response = new Response();
        $response->getBody()->write($this->smarty->fetch('domain/home/index.tpl'));

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
