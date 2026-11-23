<?php

declare(strict_types=1);

namespace App\Category\Http\ViewModel;

use App\Shared\Http\ViewModel\PageViewModel;

final class CategoryListViewModel extends PageViewModel
{
    public function __construct(
        string $title,
        public readonly array $categories,
    ) {
        parent::__construct($title);
    }
}
