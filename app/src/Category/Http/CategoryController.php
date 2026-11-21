<?php

declare(strict_types=1);

namespace App\Category\Http;

use App\Category\Application\CategoryService;
use App\Category\Http\ViewModel\CategoryViewModel;
use App\Shared\Application\DTO\Input\PageListRequestDto;
use App\Shared\Http\ViewModel\PaginationViewModel;
use Laminas\Diactoros\Response;
use League\Route\UrlGeneratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty\Smarty;

final class CategoryController
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly Smarty $smarty,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function show(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $slug = (string) $args['slug'];
        $query = $request->getQueryParams();
        $pagination = new PageListRequestDto(
            page: (int) ($query['page'] ?? 1),
            limit: (int) ($query['limit'] ?? 10),
        );

        $result = $this->categoryService->findBySlug($slug, $pagination);

        $view = new CategoryViewModel(
            title: $result->title,
            description: $result->description ?? '',
            articles: $result->articles,
            pagination: new PaginationViewModel(
                currentPage: $pagination->page,
                total: max(1, (int) ceil($result->total / $pagination->limit)),
                baseUrl: $this->urlGenerator->generateUrl(
                    'category.show',
                    ['slug' => rawurlencode($result->slug)],
                ),
                limit: $pagination->limit,
            ),
        );
        $this->smarty->assign('view', $view);

        $response = new Response();
        $response->getBody()->write($this->smarty->fetch('domain/category/show.tpl'));

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
