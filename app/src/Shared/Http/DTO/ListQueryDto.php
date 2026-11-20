<?php

declare(strict_types=1);

namespace App\Shared\Http\DTO;

class ListQueryDto
{
    public readonly string $order;

    public function __construct(
        public readonly string $sort = 'id',
        string $order = 'DESC',
    ) {
        $this->order = self::normalizeOrder($order);
    }

    public static function fromArray(array $query): self
    {
        return new self(
            sort: (string) ($query['sort'] ?? 'id'),
            order: (string) ($query['order'] ?? 'DESC'),
        );
    }

    private static function normalizeOrder(string $order): string
    {
        if (strtolower($order) === 'asc') {
            return 'ASC';
        }

        return 'DESC';
    }
}
