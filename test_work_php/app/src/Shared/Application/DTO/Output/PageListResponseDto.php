<?php

declare(strict_types=1);

namespace App\Shared\Application\DTO\Output;

final class PageListResponseDto extends ListResponseDto
{
    public function __construct(
        array $items,
        public readonly int $total,
    ) {
        parent::__construct($items);
    }
}
