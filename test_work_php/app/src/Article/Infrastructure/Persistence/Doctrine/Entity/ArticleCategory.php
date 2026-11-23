<?php

declare(strict_types=1);

namespace App\Article\Infrastructure\Persistence\Doctrine\Entity;

use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'article_category')]
final class ArticleCategory
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'categoryLinks')]
    #[ORM\JoinColumn(
        name: 'article_id',
        referencedColumnName: 'id',
        nullable: false,
        onDelete: 'CASCADE',
    )]
    private Article $article;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'articleLinks')]
    #[ORM\JoinColumn(
        name: 'category_id',
        referencedColumnName: 'id',
        nullable: false,
        onDelete: 'CASCADE',
    )]
    private Category $category;

    #[ORM\Column(
        name: 'is_primary',
        type: Types::BOOLEAN,
        options: ['default' => false],
    )]
    private bool $isPrimary;

    public function __construct(
        Article $article,
        Category $category,
        bool $isPrimary,
    ) {
        $this->article = $article;
        $this->category = $category;
        $this->isPrimary = $isPrimary;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }
}
