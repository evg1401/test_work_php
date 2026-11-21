<?php

declare(strict_types=1);

namespace App\Article\Infrastructure\Persistence\Doctrine\Repository;

use App\Article\Infrastructure\Persistence\Doctrine\Entity\Article;
use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
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
            ->select('article', 'category')
            ->from(Article::class, 'article')
            ->leftJoin('article.categories', 'category')
            ->where('article.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /** @return list<Article> */
    public function findByCategory(Category $category, int $page, int $limit): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('article')
            ->from(Article::class, 'article')
            ->innerJoin('article.categories', 'category')
            ->where('category = :category')
            ->setParameter('category', $category)
            ->orderBy('article.views', 'DESC')
            ->addOrderBy('article.publishedAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
