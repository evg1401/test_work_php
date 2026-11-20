<?php

declare(strict_types=1);

namespace App;

use App\Routing\RouteDispatcher;

final class Application
{
    public function run(): void
    {
        (new RouteDispatcher())->dispatch();
    }
}
