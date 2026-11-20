<?php

declare(strict_types=1);

use App\Home\Http\HomeController;

return [
    [
        'method' => 'GET',
        'path' => '/',
        'controller' => HomeController::class,
        'handler' => 'index',
    ],
];
