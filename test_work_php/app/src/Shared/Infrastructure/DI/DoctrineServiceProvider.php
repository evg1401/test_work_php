<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\DI;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class DoctrineServiceProvider extends AbstractServiceProvider
{
    public function __construct(
        private readonly array $databaseConfig,
        private readonly bool $isDevMode,
        private readonly string $projectRoot,
    ) {}

    public function provides(string $id): bool
    {
        return $id === EntityManagerInterface::class;
    }

    public function register(): void
    {
        $databaseConfig = $this->databaseConfig;
        $isDevMode = $this->isDevMode;
        $projectRoot = $this->projectRoot;

        $this->getContainer()->addShared(
            EntityManagerInterface::class,
            static function () use ($databaseConfig, $isDevMode, $projectRoot): EntityManagerInterface {
                $ormConfig = ORMSetup::createAttributeMetadataConfig(
                    paths: [$projectRoot . '/app/src'],
                    isDevMode: $isDevMode,
                );
                $ormConfig->enableNativeLazyObjects(true);
                $connection = DriverManager::getConnection($databaseConfig, $ormConfig);

                return new EntityManager($connection, $ormConfig);
            },
        );
    }
}
