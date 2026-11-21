<?php

declare(strict_types=1);

namespace App\Article\Application;

use App\Article\Application\DTO\Output\ArticleDetailDto;
use App\Article\Infrastructure\Persistence\Doctrine\Repository\ArticleRepository;
use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use League\Route\Http\Exception\NotFoundException;

final class ArticleService
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
    ) {}

    public function findBySlug(string $slug): ArticleDetailDto
    {
        $article = $this->articleRepository->findBySlug($slug);

        if ($article === null) {
            throw new NotFoundException('Такой статьи не существует');
        }

        return new ArticleDetailDto(
            id: (int) $article->getId(),
            title: $article->getTitle(),
            slug: $article->getSlug(),
            image: $article->getImage(),
            description: $article->getDescription(),
            text: $article->getText(),
            views: $article->getViews(),
            publishedAt: $article->getPublishedAt(),
            categories: array_map(
                static fn (Category $category): array => [
                    'title' => $category->getTitle(),
                    'slug' => $category->getSlug(),
                    'description' => $category->getDescription(),
                ],
                $article->getCategories()->toArray(),
            ),
        );
    }
}
