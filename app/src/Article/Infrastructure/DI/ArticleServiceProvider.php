<?php

declare(strict_types=1);

namespace App\Article\Infrastructure\DI;

use App\Article\Application\ArticleService;
use App\Article\Http\ArticleController;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Smarty\Smarty;

final class ArticleServiceProvider extends AbstractServiceProvider
{
    private const SERVICES = [
        ArticleService::class,
        ArticleController::class,
    ];

    public function provides(string $id): bool
    {
        return in_array($id, self::SERVICES, true);
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(ArticleService::class);
        $container
            ->add(ArticleController::class)
            ->addArgument(ArticleService::class)
            ->addArgument(Smarty::class);
    }
}
