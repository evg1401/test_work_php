<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\DI;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Smarty\Smarty;

final class SmartyServiceProvider extends AbstractServiceProvider
{
    public function __construct(
        private readonly string $projectRoot,
    ) {}

    public function provides(string $id): bool
    {
        return $id === Smarty::class;
    }

    public function register(): void
    {
        $projectRoot = $this->projectRoot;

        $this->getContainer()->addShared(
            Smarty::class,
            static function () use ($projectRoot): Smarty {
                $smarty = new Smarty();
                $smarty->setTemplateDir($projectRoot . '/resources/views');
                $smarty->setCompileDir(sys_get_temp_dir());

                return $smarty;
            },
        );
    }
}
