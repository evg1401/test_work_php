<?php

declare(strict_types=1);

namespace App\Category\Application;

use App\Article\Infrastructure\Persistence\Doctrine\Repository\ArticleRepository;
use App\Category\Application\DTO\Output\CategoriesWithLatestArticlesDto;
use App\Category\Application\DTO\Output\CategoryDetailDto;
use App\Category\Infrastructure\Persistence\Doctrine\Repository\CategoryRepository;
use App\Shared\Application\DTO\Input\PageListRequestDto;
use League\Route\Http\Exception\NotFoundException;

final class CategoryService
{
    private const LATEST_ARTICLES_LIMIT = 3;

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleRepository $articleRepository,
    ) {}

    public function findWithLatestArticles(): CategoriesWithLatestArticlesDto
    {
        $rows = $this->categoryRepository->findWithLatestArticles(self::LATEST_ARTICLES_LIMIT);

        $categories = [];
        if ($rows === []) {
            return new CategoriesWithLatestArticlesDto($categories);
        }

        foreach ($rows as $row) {
            $categoryId = $row->categoryId;

            if (!isset($categories[$categoryId])) {
                $categories[$categoryId] = [
                    'id' => $categoryId,
                    'title' => $row->categoryTitle,
                    'slug' => $row->categorySlug,
                    'articles' => [],
                ];
            }

            $categories[$categoryId]['articles'][] = [
                'id' => $row->articleId,
                'title' => $row->articleTitle,
                'slug' => $row->articleSlug,
                'image' => $row->articleImage,
                'description' => $row->articleDescription,
                'views' => $row->articleViews,
                'publishedAt' => $row->articlePublishedAt,
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
            $pagination->limit,
            $pagination->offset,
        );

        return new CategoryDetailDto(
            title: $category->getTitle(),
            description: $category->getDescription(),
            slug: $category->getSlug(),
            articles: $articles,
            total: $this->categoryRepository->countArticlesByCategory($category),
        );
    }
}
