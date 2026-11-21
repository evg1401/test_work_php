<?php

declare(strict_types=1);

namespace App\Article\Http;

use App\Article\Application\ArticleService;
use App\Article\Http\ViewModel\ArticleDetailViewModel;
use Laminas\Diactoros\Response;
use League\Route\UrlGeneratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty\Smarty;

final class ArticleController
{
    public function __construct(
        private readonly ArticleService $articleService,
        private readonly Smarty $smarty,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function show(ServerRequestInterface $_request, array $args): ResponseInterface
    {
        $result = $this->articleService->findBySlug((string) $args['slug']);

        $categories = array_map(
            fn(array $category): array => [
                'title' => $category['title'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'url' => $this->urlGenerator->generateUrl(
                    'category.show',
                    ['slug' => rawurlencode($category['slug'])],
                ),
            ],
            $result->categories,
        );

        $view = new ArticleDetailViewModel(
            title: $result->title,
            image: $result->image,
            description: $result->description,
            text: $result->text,
            views: $result->views,
            publishedAt: $result->publishedAt->format('d.m.Y H:i'),
            categories: $categories,
        );
        $this->smarty->assign('view', $view);

        $response = new Response();
        $response->getBody()->write($this->smarty->fetch('domain/article/show.tpl'));

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
