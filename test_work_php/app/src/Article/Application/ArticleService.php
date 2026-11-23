<?php

declare(strict_types=1);

namespace App\Article\Application;

use App\Article\Application\DTO\Output\ArticleDetailDto;
use App\Article\Application\DTO\Output\RelatedArticlesDto;
use App\Article\Infrastructure\Persistence\Doctrine\Repository\ArticleRepository;
use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use League\Route\Http\Exception as HttpException;
use League\Route\Http\Exception\NotFoundException;
use UnexpectedValueException;

final class ArticleService
{
    private const RELATED_ARTICLES_LIMIT = 3;

    public function __construct(
        private readonly ArticleRepository $articleRepository,
    ) {}

    public function findBySlug(string $slug): ArticleDetailDto
    {
        $article = $this->articleRepository->findBySlug($slug);

        if ($article === null) {
            throw new NotFoundException('Такой статьи не существует');
        }

        $articleId = $article->getId();

        if ($articleId === null) {
            throw new HttpException(
                status: 500,
                message: 'Внутренняя ошибка сервера.',
                previous: new UnexpectedValueException(
                    'Репозиторий вернул статью без идентификатора.',
                ),
            );
        }

        $relatedArticles = $this->findRelatedArticleBySlug($slug);

        return new ArticleDetailDto(
            id: $articleId,
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
            relatedArticles: $relatedArticles->articles,
        );
    }

    // загрузка похожих статей из общих категорий кроме основной (is_primary)
    private function findRelatedArticleBySlug(string $slug): RelatedArticlesDto
    {
        $articles = $this->articleRepository->findRelatedArticlesBySlug(
            $slug,
            self::RELATED_ARTICLES_LIMIT,
        );

        return new RelatedArticlesDto(
            articles: $articles,
        );
    }
}
