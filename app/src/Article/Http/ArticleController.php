<?php

declare(strict_types=1);

namespace App\Article\Http;

use App\Article\Application\ArticleService;
use App\Article\Http\ViewModel\ArticleDetailViewModel;
use App\Article\Http\ViewModel\ArticleViewModel;
use App\Shared\Http\ViewModel\PaginationViewModel;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty\Smarty;

final class ArticleController
{
    public function __construct(
        private readonly ArticleService $articleService,
        private readonly Smarty $smarty,
    ) {}

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $view = new ArticleViewModel(
            title: 'Articles',
            text: 'Текст статьи',
            pagination: new PaginationViewModel(
                currentPage: 1,
                totalPages: 1,
                baseUrl: '/articles',
            ),
        );
        $this->smarty->assign('view', $view);

        $response = new Response();
        $response->getBody()->write(
            $this->smarty->fetch('domain/article/index.tpl'),
        );

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }

    public function show(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $articleId = (int) $args['id'];
        $view = new ArticleDetailViewModel(
            title: sprintf('Статья %d', $articleId),
            articleId: $articleId,
            description: 'Описание',
            text: 'Текст',
            views: 1,
            categories: [
                ['id' => 1, 'title' => 'категория 1'],
                ['id' => 2, 'title' => 'категория 2'],
            ],
        );
        $this->smarty->assign('view', $view);

        $response = new Response();
        $response->getBody()->write($this->smarty->fetch('domain/article/show.tpl'));

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
