<?php

declare(strict_types=1);

namespace App\Home\Http\ViewModel;

use App\Shared\Http\ViewModel\PageViewModel;

final class HomeViewModel extends PageViewModel
{
    /**
     * @param list<array{
     *     id: int,
     *     title: string,
     *     slug: string,
     *     url: string,
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
        string $title,
        public readonly array $categories,
    ) {
        parent::__construct($title);
    }
}
