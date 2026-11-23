<?php

declare(strict_types=1);

namespace App\Shared\Application\DTO\Input;

final class PageListRequestDto
{
    public readonly int $page;
    public readonly int $limit;
    public readonly int $offset;

    public function __construct(
        int $page = 1,
        int $limit = 100,
    ) {
        $this->page = max(1, $page);

        if ($limit <= 0) $limit = 100;
        $this->limit = min($limit, 100);

        $this->offset = ($this->page - 1) * $this->limit;
    }
}
