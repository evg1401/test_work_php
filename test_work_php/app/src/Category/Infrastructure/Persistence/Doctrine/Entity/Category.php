<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Persistence\Doctrine\Entity;

use App\Article\Infrastructure\Persistence\Doctrine\Entity\Article;
use App\Article\Infrastructure\Persistence\Doctrine\Entity\ArticleCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'categories')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 256, unique: true)]
    private string $title;

    #[ORM\Column(type: Types::STRING, length: 256, unique: true)]
    private string $slug;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    /** @var Collection<int, ArticleCategory> */
    #[ORM\OneToMany(targetEntity: ArticleCategory::class, mappedBy: 'category')]
    private Collection $articleLinks;

    public function __construct(string $title, string $slug, ?string $description)
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->articleLinks = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /** @return list<Article> */
    public function getArticles(): array
    {
        return array_values(
            $this->articleLinks
                ->map(
                    static fn (ArticleCategory $link): Article => $link->getArticle(),
                )
                ->toArray(),
        );
    }

    /** @return Collection<int, ArticleCategory> */
    public function getArticleLinks(): Collection
    {
        return $this->articleLinks;
    }

    public function addArticleLink(ArticleCategory $link): void
    {
        if (!$this->articleLinks->contains($link)) {
            $this->articleLinks->add($link);
        }
    }

    public function removeArticleLink(ArticleCategory $link): void
    {
        $this->articleLinks->removeElement($link);
    }
}
