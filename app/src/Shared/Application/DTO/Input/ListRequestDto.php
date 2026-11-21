<?php

declare(strict_types=1);

namespace App\Shared\Application\DTO\Input;

class ListRequestDto
{
    public readonly string $order;

    public function __construct(
        public readonly string $sort = 'id',
        string $order = 'DESC',
    ) {
        $this->order = self::normalizeOrder($order);
    }

    private static function normalizeOrder(string $order): string
    {
        if (strtolower($order) === 'asc') {
            return 'ASC';
        }

        return 'DESC';
    }
}
