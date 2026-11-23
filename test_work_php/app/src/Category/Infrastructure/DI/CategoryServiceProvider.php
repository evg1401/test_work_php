<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\DI;

use App\Article\Infrastructure\Persistence\Doctrine\Repository\ArticleRepository;
use App\Category\Application\CategoryService;
use App\Category\Http\CategoryController;
use App\Category\Infrastructure\Persistence\Doctrine\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\UrlGeneratorInterface;
use Smarty\Smarty;

final class CategoryServiceProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        return in_array($id, [
            CategoryService::class,
            CategoryController::class,
            CategoryRepository::class,
        ], true);
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container
            ->addShared(CategoryRepository::class)
            ->addArgument(EntityManagerInterface::class);

        $container
            ->addShared(CategoryService::class)
            ->addArgument(CategoryRepository::class)
            ->addArgument(ArticleRepository::class);

        $container
            ->add(CategoryController::class)
            ->addArgument(CategoryService::class)
            ->addArgument(Smarty::class)
            ->addArgument(UrlGeneratorInterface::class);
    }
}
