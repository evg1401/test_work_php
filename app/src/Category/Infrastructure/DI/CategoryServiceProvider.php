<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\DI;

use App\Category\Http\CategoryController;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Smarty\Smarty;

final class CategoryServiceProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        return $id === CategoryController::class;
    }

    public function register(): void
    {
        $this->getContainer()
            ->add(CategoryController::class)
            ->addArgument(Smarty::class);
    }
}
