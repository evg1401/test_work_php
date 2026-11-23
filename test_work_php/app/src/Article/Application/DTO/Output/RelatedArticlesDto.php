<?php

declare(strict_types=1);

namespace App\Article\Application\DTO\Output;

use DateTimeImmutable;

final class RelatedArticlesDto
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
        public readonly array $articles,
    ) {}
}
