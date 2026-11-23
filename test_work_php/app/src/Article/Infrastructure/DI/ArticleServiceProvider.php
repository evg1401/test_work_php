<?php

declare(strict_types=1);

namespace App\Article\Infrastructure\DI;

use App\Article\Application\ArticleService;
use App\Article\Http\ArticleController;
use App\Article\Infrastructure\Persistence\Doctrine\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\UrlGeneratorInterface;
use Smarty\Smarty;

final class ArticleServiceProvider extends AbstractServiceProvider
{
    private const SERVICES = [
        ArticleService::class,
        ArticleController::class,
        ArticleRepository::class,
    ];

    public function provides(string $id): bool
    {
        return in_array($id, self::SERVICES, true);
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container
            ->addShared(ArticleRepository::class)
            ->addArgument(EntityManagerInterface::class);

        $container
            ->addShared(ArticleService::class)
            ->addArgument(ArticleRepository::class);

        $container
            ->add(ArticleController::class)
            ->addArgument(ArticleService::class)
            ->addArgument(Smarty::class)
            ->addArgument(UrlGeneratorInterface::class);
    }
}
