<?php

declare(strict_types=1);

namespace App\Shared\Application\DTO\Input;

final class PageListRequestDto extends ListRequestDto
{
    public readonly int $page;
    public readonly int $limit;
    public readonly int $offset;

    public function __construct(
        int $page = 1,
        int $limit = 100,
        string $sort = 'id',
        string $order = 'DESC',
    ) {
        $this->page = max(1, $page);
        $this->limit = min(max(1, $limit), 100);

        $this->offset = ($this->page - 1) * $this->limit;

        parent::__construct($sort, $order);
    }
}
