<?php

declare(strict_types=1);

namespace App\Category\Application;

use App\Article\Infrastructure\Persistence\Doctrine\Entity\Article;
use App\Article\Infrastructure\Persistence\Doctrine\Repository\ArticleRepository;
use App\Category\Application\DTO\Output\CategoriesWithLatestArticlesDto;
use App\Category\Application\DTO\Output\CategoryDetailDto;
use App\Category\Infrastructure\Persistence\Doctrine\Repository\CategoryRepository;
use App\Shared\Application\DTO\Input\PageListRequestDto;
use League\Route\Http\Exception\NotFoundException;

final class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleRepository $articleRepository,
    ) {}

    public function findWithLatestArticles(): CategoriesWithLatestArticlesDto
    {
        $rows = $this->categoryRepository->findWithLatestArticles(3);

        $categories = [];
        if (count($rows) === 0) {
            return new CategoriesWithLatestArticlesDto($categories);
        }

        foreach ($rows as $row) {
            $categoryId = (int) $row['category_id'];

            if (!isset($categories[$categoryId])) {
                $categories[$categoryId] = [
                    'id' => $categoryId,
                    'title' => $row['category_title'],
                    'slug' => $row['category_slug'],
                    'articles' => [],
                ];
            }

            $categories[$categoryId]['articles'][] = [
                'id' => (int) $row['id'],
                'title' => $row['title'],
                'slug' => $row['slug'],
                'image' => $row['image'],
                'description' => $row['description'],
                'text' => $row['text'],
                'views' => (int) $row['views'],
                'publishedAt' => $row['published_at'],
            ];
        }

        return new CategoriesWithLatestArticlesDto(
            array_values($categories),
        );
    }

    public function findBySlug(string $slug, PageListRequestDto $pagination): CategoryDetailDto
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if ($category === null) {
            throw new NotFoundException('Такой категории не существует');
        }

        $articles = $this->articleRepository->findByCategory(
            $category,
            $pagination->page,
            $pagination->limit,
        );

        return new CategoryDetailDto(
            title: $category->getTitle(),
            description: $category->getDescription(),
            slug: $category->getSlug(),
            articles: array_map(
                static fn(Article $article): array => [
                    'title' => $article->getTitle(),
                    'slug' => $article->getSlug(),
                    'description' => $article->getDescription(),
                ],
                $articles,
            ),
            total: $this->categoryRepository->countArticlesBySlug($slug),
        );
    }
}
