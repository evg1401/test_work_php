<?php

declare(strict_types=1);

use App\Article\Http\ArticleController;

return [
    [
        'method' => 'GET',
        'path' => '/articles',
        'controller' => ArticleController::class,
        'handler' => 'index',
    ],
];
