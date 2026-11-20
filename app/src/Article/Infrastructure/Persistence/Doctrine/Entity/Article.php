<?php

declare(strict_types=1);

namespace App\Article\Infrastructure\Persistence\Doctrine\Entity;

use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
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

    #[ORM\Column(type: Types::STRING, length: 256)]
    private string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: Types::TEXT)]
    private string $text;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $views;

    /** @var Collection<int, Category> */
    #[ORM\ManyToMany(targetEntity: Category::class)]
    #[ORM\JoinTable(name: 'article_category')]
    #[ORM\JoinColumn(
        name: 'article_id',
        referencedColumnName: 'id',
        nullable: false,
        onDelete: 'CASCADE',
    )]
    #[ORM\InverseJoinColumn(
        name: 'category_id',
        referencedColumnName: 'id',
        nullable: false,
        onDelete: 'CASCADE',
    )]
    private Collection $categories;

    public function __construct(
        string $title,
        ?string $description,
        string $text,
        int $views = 0,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->text = $text;
        $this->views = $views;
        $this->categories = new ArrayCollection();
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

    /** @return Collection<int, Category> */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): void
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
    }

    public function removeCategory(Category $category): void
    {
        $this->categories->removeElement($category);
    }
}
