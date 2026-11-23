<?php

declare(strict_types=1);

namespace App\Home\Http;

use App\Category\Application\CategoryService;
use App\Home\Http\ViewModel\HomeViewModel;
use Laminas\Diactoros\Response;
use League\Route\UrlGeneratorInterface;
use Psr\Http\Message\ResponseInterface;
use Smarty\Smarty;

final class HomeController
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly Smarty $smarty,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function index(): ResponseInterface
    {
        $result = $this->categoryService->findWithLatestArticles();
        $categories = array_map(
            fn (array $category): array => [
                ...$category,
                'url' => $this->urlGenerator->generateUrl(
                    'category.show',
                    ['slug' => rawurlencode($category['slug'])],
                ),
            ],
            $result->categories,
        );

        $view = new HomeViewModel(
            title: 'Главная',
            categories: $categories,
        );
        $this->smarty->assign('view', $view);

        $response = new Response();
        $response->getBody()->write($this->smarty->fetch('domain/home/index.tpl'));

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
