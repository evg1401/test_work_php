<?php

declare(strict_types=1);

namespace App\Shared\Http\ViewModel;

final class ErrorViewModel extends PageViewModel
{
    public function __construct(
        string $title,
        public readonly int $statusCode,
        public readonly string $message,
    ) {
        parent::__construct($title);
    }
}
