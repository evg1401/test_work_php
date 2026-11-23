<?php

declare(strict_types=1);

namespace App\Category\Application\DTO\Output;

use DateTimeImmutable;

final class CategoryDetailDto
{
    /**
     * @param list<array{
     *     title: string,
     *     slug: string,
     *     image: ?string,
     *     description: ?string,
     *     views: int,
     *     publishedAt: DateTimeImmutable
     * }> $articles
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $slug,
        public readonly array $articles,
        public readonly int $total,
    ) {}
}
