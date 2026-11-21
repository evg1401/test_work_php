<?php

declare(strict_types=1);

namespace App\Article\Http\ViewModel;

use App\Shared\Http\ViewModel\PageViewModel;

final class ArticleDetailViewModel extends PageViewModel
{
    public function __construct(
        string $title,
        public readonly string $articleSlug,
        public readonly string $description,
        public readonly string $text,
        public readonly int $views,
        public readonly array $categories,
    ) {
        parent::__construct($title);
    }
}
