<?php

declare(strict_types=1);

namespace App\Home\Infrastructure\DI;

use App\Category\Application\CategoryService;
use App\Home\Http\HomeController;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Smarty\Smarty;

final class HomeServiceProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        return $id === HomeController::class;
    }

    public function register(): void
    {
        $this->getContainer()
            ->add(HomeController::class)
            ->addArgument(CategoryService::class)
            ->addArgument(Smarty::class);
    }
}
