<?php

declare(strict_types=1);

use App\Article\Application\ArticleService;
use App\Article\Http\ArticleController;

return [
    [
        'method' => 'GET',
        'path' => '/articles',
        'controller' => new ArticleController(new ArticleService()),
        'handler' => 'index',
    ],
];
