<?php

declare(strict_types=1);

use App\Category\Http\CategoryController;

return [
    [
        'method' => 'GET',
        'path' => '/categories',
        'controller' => CategoryController::class,
        'handler' => 'index',
    ],
    [
        'method' => 'GET',
        'path' => '/categories/{slug}',
        'controller' => CategoryController::class,
        'handler' => 'show',
    ],
];
