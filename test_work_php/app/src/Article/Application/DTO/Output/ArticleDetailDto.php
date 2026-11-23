<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Output;

use DateTimeImmutable;

final class ArticleDetailDto
{
    /**
     * @param list<array{title: string, slug: string, description: ?string}> $categories
     * @param list<array{
     *     title: string,
     *     slug: string,
     *     image: ?string,
     *     description: ?string,
     *     views: int,
     *     publishedAt: DateTimeImmutable
     * }> $relatedArticles
     */
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $slug,
        public readonly ?string $image,
        public readonly ?string $description,
        public readonly string $text,
        public readonly int $views,
        public readonly DateTimeImmutable $publishedAt,
        public readonly array $categories,
        public readonly array $relatedArticles,
    ) {}
}
