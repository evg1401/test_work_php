<?php

declare(strict_types=1);

use App\Category\Http\CategoryController;

return [
    [
        'method' => 'GET',
        'path' => '/categories/{id:number}',
        'controller' => CategoryController::class,
        'handler' => 'show',
    ],
];
