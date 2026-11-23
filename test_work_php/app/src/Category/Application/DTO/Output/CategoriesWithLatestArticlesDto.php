<?php

declare(strict_types=1);

namespace App\Category\Application\DTO\Output;

final class CategoriesWithLatestArticlesDto
{
    /**
     * @param list<array{
     *     id: int,
     *     title: string,
     *     slug: string,
     *     articles: list<array{
     *         id: int,
     *         title: string,
     *         slug: string,
     *         image: ?string,
     *         description: ?string,
     *         views: int,
     *         publishedAt: string
     *     }>
     * }> $categories
     */
    public function __construct(
        public readonly array $categories,
    ) {}
}
