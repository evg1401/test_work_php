<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;

final class CategoryRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

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

        $rows = $this->entityManager
            ->getConnection()
            ->fetchAllAssociative(
                $sql,
                ['articlesLimit' => $articlesLimit],
                ['articlesLimit' => ParameterType::INTEGER],
            );

        $categories = [];

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

        return array_values($categories);
    }
}
