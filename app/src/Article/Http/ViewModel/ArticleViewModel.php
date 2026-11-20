<?php

declare(strict_types=1);

namespace App\Article\Http\ViewModel;

use App\Shared\Http\ViewModel\PageViewModel;
use App\Shared\Http\ViewModel\PaginationViewModel;

final class ArticleViewModel extends PageViewModel
{
    public function __construct(
        string $title,
        public readonly string $text,
        public readonly PaginationViewModel $pagination,
    ) {
        parent::__construct($title);
    }
}
