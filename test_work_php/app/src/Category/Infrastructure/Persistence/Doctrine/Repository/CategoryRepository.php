<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Persistence\Doctrine\Repository;

use App\Article\Infrastructure\Persistence\Doctrine\Entity\ArticleCategory;
use App\Category\Application\DTO\Output\CategoryLatestArticleDto;
use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;
use UnexpectedValueException;

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

    public function countArticlesByCategory(Category $category): int
    {
        return (int) $this->entityManager
            ->createQueryBuilder()
            ->select('COUNT(IDENTITY(articleLink.article))')
            ->from(ArticleCategory::class, 'articleLink')
            ->where('articleLink.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /** @return list<CategoryLatestArticleDto> */
    public function findWithLatestArticles(int $articlesLimit): array
    {
        $sql = <<<'SQL'
            WITH ranked_articles AS (
                SELECT
                    article_category.category_id,
                    article_category.article_id,
                    article.published_at,
                    ROW_NUMBER() OVER (
                        PARTITION BY article_category.category_id
                        ORDER BY article.published_at DESC
                    ) AS article_rank
                FROM article_category
                INNER JOIN articles article
                    ON article.id = article_category.article_id
            ),
            lim_articles AS (
                SELECT
                    category_id,
                    article_id,
                    published_at
                FROM ranked_articles
                WHERE article_rank <= :articlesLimit
            )
            SELECT
                lim_articles.category_id,
                category.title AS category_title,
                category.slug AS category_slug,
                article.id,
                article.title,
                article.image,
                article.description,
                article.views,
                article.slug,
                lim_articles.published_at
            FROM lim_articles
            INNER JOIN articles article
                ON article.id = lim_articles.article_id
            INNER JOIN categories category
                ON category.id = lim_articles.category_id
            ORDER BY lim_articles.category_id, lim_articles.published_at DESC
            SQL;

        $rows = $this->entityManager
            ->getConnection()
            ->fetchAllAssociative(
                $sql,
                ['articlesLimit' => $articlesLimit],
                ['articlesLimit' => ParameterType::INTEGER],
            );

        return array_map(
            self::mapLatestArticleRow(...),
            $rows,
        );
    }

    /** @param array<string, mixed> $row */
    private static function mapLatestArticleRow(array $row): CategoryLatestArticleDto
    {
        return new CategoryLatestArticleDto(
            categoryId: self::requireInt($row, 'category_id'),
            categoryTitle: self::requireString($row, 'category_title'),
            categorySlug: self::requireString($row, 'category_slug'),
            articleId: self::requireInt($row, 'id'),
            articleTitle: self::requireString($row, 'title'),
            articleSlug: self::requireString($row, 'slug'),
            articleImage: self::requireNullableString($row, 'image'),
            articleDescription: self::requireNullableString($row, 'description'),
            articleViews: self::requireInt($row, 'views'),
            articlePublishedAt: self::requireString($row, 'published_at'),
        );
    }

    /** @param array<string, mixed> $row */
    private static function requireInt(array $row, string $key): int
    {
        $value = self::requireValue($row, $key);

        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && preg_match('/^-?(?:0|[1-9]\d*)$/D', $value) === 1) {
            $integer = filter_var($value, FILTER_VALIDATE_INT);

            if ($integer !== false) {
                return $integer;
            }
        }

        throw self::unexpectedType($key, 'int', $value);
    }

    /** @param array<string, mixed> $row */
    private static function requireString(array $row, string $key): string
    {
        $value = self::requireValue($row, $key);

        if (!is_string($value)) {
            throw self::unexpectedType($key, 'string', $value);
        }

        return $value;
    }

    /** @param array<string, mixed> $row */
    private static function requireNullableString(array $row, string $key): ?string
    {
        $value = self::requireValue($row, $key);

        if ($value !== null && !is_string($value)) {
            throw self::unexpectedType($key, 'string|null', $value);
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $row
     * @return mixed
     */
    private static function requireValue(array $row, string $key): mixed
    {
        if (!array_key_exists($key, $row)) {
            throw new UnexpectedValueException(sprintf(
                'В результате запроса отсутствует обязательное поле "%s".',
                $key,
            ));
        }

        return $row[$key];
    }

    private static function unexpectedType(
        string $key,
        string $expectedType,
        mixed $value,
    ): UnexpectedValueException {
        return new UnexpectedValueException(sprintf(
            'Поле "%s" должно иметь тип %s, получен %s.',
            $key,
            $expectedType,
            get_debug_type($value),
        ));
    }
}
