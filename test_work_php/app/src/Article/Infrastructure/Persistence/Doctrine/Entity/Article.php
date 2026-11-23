<?php

declare(strict_types=1);

namespace App\Article\Infrastructure\Persistence\Doctrine\Entity;

use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'articles')]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 256, unique: true)]
    private string $title;

    #[ORM\Column(type: Types::STRING, length: 256, unique: true)]
    private string $slug;

    #[ORM\Column(type: Types::STRING, length: 256, nullable: true)]
    private ?string $image;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: Types::TEXT)]
    private string $text;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $views;

    #[ORM\Column(name: 'published_at', type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $publishedAt;

    /** @var Collection<int, ArticleCategory> */
    #[ORM\OneToMany(
        targetEntity: ArticleCategory::class,
        mappedBy: 'article',
        cascade: ['persist'],
        orphanRemoval: true,
    )]
    private Collection $categoryLinks;

    public function __construct(
        string $title,
        string $slug,
        ?string $image,
        ?string $description,
        string $text,
        DateTimeImmutable $publishedAt,
        int $views = 0,
    ) {
        $this->title = $title;
        $this->slug = $slug;
        $this->image = $image;
        $this->description = $description;
        $this->text = $text;
        $this->publishedAt = $publishedAt;
        $this->views = $views;
        $this->categoryLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTimeImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /** @return Collection<int, Category> */
    public function getCategories(): Collection
    {
        return $this->categoryLinks->map(
            static fn (ArticleCategory $link): Category => $link->getCategory(),
        );
    }

    /** @return Collection<int, ArticleCategory> */
    public function getCategoryLinks(): Collection
    {
        return $this->categoryLinks;
    }

    public function addCategory(Category $category, bool $isPrimary = false): void
    {
        foreach ($this->categoryLinks as $link) {
            if ($link->getCategory() === $category) {
                return;
            }

            if ($isPrimary && $link->isPrimary()) {
                throw new \LogicException('У статьи уже определена основная категория.');
            }
        }

        $link = new ArticleCategory($this, $category, $isPrimary);
        $this->categoryLinks->add($link);
        $category->addArticleLink($link);
    }

    public function removeCategory(Category $category): void
    {
        foreach ($this->categoryLinks as $link) {
            if ($link->getCategory() !== $category) {
                continue;
            }

            $this->categoryLinks->removeElement($link);
            $category->removeArticleLink($link);

            return;
        }
    }
}
