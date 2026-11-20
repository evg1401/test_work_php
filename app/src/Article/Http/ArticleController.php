<?php

declare(strict_types=1);

namespace App\Article\Http;

use App\Article\Application\ArticleService;
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
        $this->smarty->assign('title', 'Articles');

        $response = new Response();
        $response->getBody()->write(
            $this->smarty->fetch('articles/index.tpl'),
        );

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
