<?php

declare(strict_types=1);

namespace App\Shared\Application\DTO\Output;

class ListResponseDto
{
    public readonly int $count;

    public function __construct(
        public readonly array $items,
    ) {
        $this->count = count($items);
    }
}
