<?php

declare(strict_types=1);

namespace App\Shared\Http\DTO;

final class PaginationQueryDto extends ListQueryDto
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $limit = 100,
        string $sort = 'id',
        string $order = 'DESC',
    ) {
        parent::__construct($sort, $order);
    }

    public static function fromArray(array $query): self
    {
        return new self(
            page: (int) ($query['page'] ?? 1),
            limit: (int) ($query['limit'] ?? 100),
            sort: (string) ($query['sort'] ?? 'id'),
            order: (string) ($query['order'] ?? 'DESC'),
        );
    }

}
