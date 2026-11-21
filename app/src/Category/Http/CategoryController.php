<?php

declare(strict_types=1);

namespace App\Category\Http;

use App\Category\Application\CategoryService;
use App\Category\Http\ViewModel\CategoryListViewModel;
use App\Category\Http\ViewModel\CategoryViewModel;
use App\Shared\Http\ViewModel\PaginationViewModel;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty\Smarty;

final class CategoryController
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly Smarty $smarty,
    ) {}

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $view = new CategoryListViewModel(
            title: 'Категории',
            categories: $this->categoryService->findWithLatestArticles(),
        );
        $this->smarty->assign('view', $view);

        $response = new Response();
        $response->getBody()->write(
            $this->smarty->fetch('domain/category/index.tpl'),
        );

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }

    public function show(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $categorySlug = (string) $args['slug'];
        $currentPage = max(1, (int) ($request->getQueryParams()['page'] ?? 1));
        $view = new CategoryViewModel(
            title: sprintf('Категория %s', $categorySlug),
            categorySlug: $categorySlug,
            description: 'Статьи',
            articles: [
                ['slug' => 'article-1', 'title' => 'статья 1', 'description' => 'Описание'],
                ['slug' => 'article-2', 'title' => 'статья 2', 'description' => 'Описание'],
            ],
            pagination: new PaginationViewModel(
                currentPage: $currentPage,
                totalPages: 3,
                baseUrl: sprintf('/categories/%s', $categorySlug),
            ),
        );
        $this->smarty->assign('view', $view);

        $response = new Response();
        $response->getBody()->write($this->smarty->fetch('domain/category/show.tpl'));

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
