<?php

declare(strict_types=1);

namespace App\Home\Http\ViewModel;

use App\Shared\Http\ViewModel\PageViewModel;

final class HomeViewModel extends PageViewModel
{
    public function __construct(
        string $title,
        public readonly array $categories,
    ) {
        parent::__construct($title);
    }
}
