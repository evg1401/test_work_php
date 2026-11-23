<?php

declare(strict_types=1);

use App\Category\Http\CategoryController;

return [
    [
        'name' => 'category.show',
        'method' => 'GET',
        'path' => '/categories/{slug}',
        'controller' => CategoryController::class,
        'handler' => 'show',
    ],
];
