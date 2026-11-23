<?php

declare(strict_types=1);

namespace App\Article\Http\ViewModel;

use App\Shared\Http\ViewModel\PageViewModel;

final class ArticleDetailViewModel extends PageViewModel
{
    /**
     * @param list<array{
     *     title: string,
     *     slug: string,
     *     description: ?string,
     *     url: string
     * }> $categories
     * @param list<array{
     *     title: string,
     *     image: ?string,
     *     description: ?string,
     *     views: int,
     *     publishedAt: string,
     *     url: string
     * }> $relatedArticles
     */
    public function __construct(
        string $title,
        public readonly ?string $image,
        public readonly ?string $description,
        public readonly string $text,
        public readonly int $views,
        public readonly string $publishedAt,
        public readonly array $categories,
        public readonly array $relatedArticles,
    ) {
        parent::__construct($title);
    }
}
