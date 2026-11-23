<?php

declare(strict_types=1);

namespace App\Category\Application\DTO\Output;

final class CategoryLatestArticleDto
{
    public function __construct(
        public readonly int $categoryId,
        public readonly string $categoryTitle,
        public readonly string $categorySlug,
        public readonly int $articleId,
        public readonly string $articleTitle,
        public readonly string $articleSlug,
        public readonly ?string $articleImage,
        public readonly ?string $articleDescription,
        public readonly int $articleViews,
        public readonly string $articlePublishedAt,
    ) {}
}
