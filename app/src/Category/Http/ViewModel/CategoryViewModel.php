<?php

declare(strict_types=1);

namespace App\Category\Http\ViewModel;

use App\Shared\Http\ViewModel\PageViewModel;
use App\Shared\Http\ViewModel\PaginationViewModel;

final class CategoryViewModel extends PageViewModel
{
    /**
     * @param list<array{title: string, slug: string, description: ?string}> $articles
     */
    public function __construct(
        string $title,
        public readonly string $description,
        public readonly array $articles,
        public readonly PaginationViewModel $pagination,
    ) {
        parent::__construct($title);
    }
}
