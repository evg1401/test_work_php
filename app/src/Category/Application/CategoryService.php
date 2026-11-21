<?php

declare(strict_types=1);

namespace App\Category\Application;

use App\Category\Infrastructure\Persistence\Doctrine\Repository\CategoryRepository;

final class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    ) {}

    public function findWithLatestArticles(): array
    {
        return $this->categoryRepository->findWithLatestArticles(3);
    }
}
