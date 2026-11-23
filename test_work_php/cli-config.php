<?php

declare(strict_types=1);

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManagerInterface;

require_once __DIR__ . '/vendor/autoload.php';

$container = require __DIR__ . '/bootstrap/container.php';
$entityManager = $container->get(EntityManagerInterface::class);

$configuration = new ConfigurationArray([
    'migrations_paths' => [
        'DoctrineMigrations' => __DIR__ . '/migrations',
    ],
    'table_storage' => [
        'table_name' => 'doctrine_migration_versions',
    ],
    'all_or_nothing' => true,
    'check_database_platform' => true,
]);

return DependencyFactory::fromEntityManager(
    $configuration,
    new ExistingEntityManager($entityManager),
);
