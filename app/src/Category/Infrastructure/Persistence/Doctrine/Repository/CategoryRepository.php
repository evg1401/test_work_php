<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Persistence\Doctrine\Repository;

use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;

final class CategoryRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function findBySlug(string $slug): ?Category
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('category')
            ->from(Category::class, 'category')
            ->where('category.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countArticlesBySlug(string $slug): int
    {
        return (int) $this->entityManager
            ->createQueryBuilder()
            ->select('COUNT(article.id)')
            ->from(Category::class, 'category')
            ->leftJoin('category.articles', 'article')
            ->where('category.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findWithLatestArticles(int $articlesLimit): array
    {
        $sql = <<<'SQL'
            SELECT
                ranked.category_id,
                ranked.category_title,
                ranked.category_slug,
                ranked.id,
                ranked.title,
                ranked.image,
                ranked.description,
                ranked.text,
                ranked.views,
                ranked.slug,
                ranked.published_at
            FROM (
                SELECT
                    article_category.category_id,
                    article.*,
                    category.title AS category_title,
                    category.slug AS category_slug,
                    ROW_NUMBER() OVER (
                        PARTITION BY article_category.category_id
                        ORDER BY article.published_at DESC
                    ) AS article_rank
                FROM article_category
                INNER JOIN articles article
                    ON article_category.article_id = article.id
                INNER JOIN categories category
                    ON article_category.category_id = category.id
            ) ranked
            WHERE ranked.article_rank <= :articlesLimit
            ORDER BY ranked.category_id, ranked.published_at DESC
            SQL;

        return $this->entityManager
            ->getConnection()
            ->fetchAllAssociative(
                $sql,
                ['articlesLimit' => $articlesLimit],
                ['articlesLimit' => ParameterType::INTEGER],
            );
    }
}
