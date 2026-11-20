<?php

declare(strict_types=1);

namespace App\Shared\Http\ViewModel;

final class PaginationViewModel
{
    public function __construct(
        public readonly int $currentPage,
        public readonly int $totalPages,
        public readonly string $baseUrl,
    ) {}
}
