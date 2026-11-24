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
                    a.id AS source_article_id,
                    ac.category_id
                FROM articles a
                JOIN article_category ac
                    ON ac.article_id = a.id
                    AND ac.is_primary = FALSE
                WHERE a.slug = :slug
                ORDER BY ac.category_id
                LIMIT :categoriesLimit
            )
            SELECT DISTINCT
                a.id,
                a.published_at
            FROM source_categories sc
            JOIN articles a
                ON a.id = (
                    SELECT candidate.id
                    FROM article_category ac
                    JOIN articles candidate
                        ON candidate.id = ac.article_id
                    WHERE ac.category_id = sc.category_id
                    AND candidate.id != sc.source_article_id
                    ORDER BY candidate.published_at DESC, candidate.id DESC
                    LIMIT 1
                )
            ORDER BY a.published_at DESC, a.id DESC
            LIMIT :articlesLimit;
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
