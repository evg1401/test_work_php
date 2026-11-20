<?php

declare(strict_types=1);

namespace App\Shared\Http\ViewModel;

abstract class PageViewModel
{
    public function __construct(
        public readonly string $title,
    ) {}
}
