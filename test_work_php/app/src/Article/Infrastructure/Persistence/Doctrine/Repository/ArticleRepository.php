<?php

declare(strict_types=1);

namespace App\Article\Infrastructure\Persistence\Doctrine\Repository;

use App\Article\Infrastructure\Persistence\Doctrine\Entity\Article;
use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;

final class ArticleRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function findBySlug(string $slug): ?Article
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('article', 'categoryLink', 'category')
            ->from(Article::class, 'article')
            ->leftJoin('article.categoryLinks', 'categoryLink')
            ->leftJoin('categoryLink.category', 'category')
            ->where('article.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return list<array{
     *     title: string,
     *     slug: string,
     *     image: ?string,
     *     description: ?string,
     *     views: int,
     *     publishedAt: \DateTimeImmutable
     * }>
     */
    public function findRelatedArticlesBySlug(string $slug, int $limit): array
    {
        return $this->findByIds(
            $this->findRelatedArticleIds($slug, $limit),
        );
    }

    /** @return list<int> */
    private function findRelatedArticleIds(string $slug, int $limit): array
    {
        $sql = <<<'SQL'
            WITH source_categories AS (
                SELECT
                    articles.id AS source_article_id,
                    article_category.category_id
                FROM articles
                INNER JOIN article_category
                    ON article_category.article_id = articles.id
                    AND article_category.is_primary = FALSE
                WHERE articles.slug = :slug
                ORDER BY article_category.category_id ASC
                LIMIT :categoriesLimit
            ),
            ranked_articles AS (
                SELECT
                    link.category_id,
                    candidate.id,
                    candidate.published_at,
                    ROW_NUMBER() OVER (
                        PARTITION BY link.category_id
                        ORDER BY candidate.published_at DESC, candidate.id DESC
                    ) AS position
                FROM source_categories
                INNER JOIN article_category link
                    ON link.category_id = source_categories.category_id
                INNER JOIN articles candidate
                    ON candidate.id = link.article_id
                    AND candidate.id != source_categories.source_article_id
            )
            SELECT DISTINCT
                ranked_articles.id,
                ranked_articles.published_at
            FROM ranked_articles
            WHERE ranked_articles.position = 1
            ORDER BY ranked_articles.published_at DESC, ranked_articles.id DESC
            LIMIT :articlesLimit
            SQL;

        return array_map(
            static fn (string|int $id): int => (int) $id,
            $this->entityManager
                ->getConnection()
                ->fetchFirstColumn(
                    $sql,
                    [
                        'slug' => $slug,
                        'categoriesLimit' => $limit,
                        'articlesLimit' => $limit,
                    ],
                    [
                        'slug' => ParameterType::STRING,
                        'categoriesLimit' => ParameterType::INTEGER,
                        'articlesLimit' => ParameterType::INTEGER,
                    ],
                ),
        );
    }

    /**
     * @param list<int> $articleIds
     * @return list<array{
     *     title: string,
     *     slug: string,
     *     image: ?string,
     *     description: ?string,
     *     views: int,
     *     publishedAt: \DateTimeImmutable
     * }>
     */
    private function findByIds(array $articleIds): array
    {
        if ($articleIds === []) {
            return [];
        }

        return $this->entityManager
            ->createQueryBuilder()
            ->select(
                'article.title AS title',
                'article.slug AS slug',
                'article.image AS image',
                'article.description AS description',
                'article.views AS views',
                'article.publishedAt AS publishedAt',
            )
            ->from(Article::class, 'article')
            ->where('article.id IN (:articleIds)')
            ->setParameter('articleIds', $articleIds)
            ->orderBy('article.publishedAt', 'DESC')
            ->addOrderBy('article.id', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @return list<array{
     *     title: string,
     *     slug: string,
     *     image: ?string,
     *     description: ?string,
     *     views: int,
     *     publishedAt: \DateTimeImmutable
     * }>
     */
    public function findByCategory(Category $category, int $limit, int $offset): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select(
                'article.title AS title',
                'article.slug AS slug',
                'article.image AS image',
                'article.description AS description',
                'article.views AS views',
                'article.publishedAt AS publishedAt',
            )
            ->from(Article::class, 'article')
            ->innerJoin('article.categoryLinks', 'categoryLink')
            ->where('categoryLink.category = :category')
            ->setParameter('category', $category)
            ->orderBy('article.views', 'DESC')
            ->addOrderBy('article.publishedAt', 'DESC')
            ->addOrderBy('article.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }
}
