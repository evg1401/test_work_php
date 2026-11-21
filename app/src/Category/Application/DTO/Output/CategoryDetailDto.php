<?php

declare(strict_types=1);

namespace App\Category\Application\DTO\Output;

final class CategoryDetailDto
{
    /**
     * @param list<array{title: string, slug: string, description: ?string}> $articles
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $slug,
        public readonly array $articles,
        public readonly int $total,
    ) {}
}
