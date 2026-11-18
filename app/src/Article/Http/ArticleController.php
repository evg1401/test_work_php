<?php

declare(strict_types=1);

namespace App\Article\Http;

use App\Article\Application\ArticleService;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ArticleController
{
    public function __construct(
        private readonly ArticleService $articleService,
    ) {}

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write('articles');

        return $response->withHeader('Content-Type', 'text/plain; charset=utf-8');
    }
}
