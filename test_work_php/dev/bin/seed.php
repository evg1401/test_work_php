<?php

declare(strict_types=1);

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Fixtures\ArticleFixture;
use Fixtures\CategoryFixture;

$projectRoot = dirname(__DIR__, 2);

require_once $projectRoot . '/vendor/autoload.php';

$config = require $projectRoot . '/bootstrap/config.php';

if ($config['is_dev_mode'] !== true) {
    throw new RuntimeException('seed разрешен только при APP_DEV=true.');
}

if (!in_array('--purge', $argv, true)) {
    throw new RuntimeException(
        'Для подтверждения очистки нужно передать параметр --purge.',
    );
}

$container = require $projectRoot . '/bootstrap/container.php';
$entityManager = $container->get(EntityManagerInterface::class);

$loader = new Loader();
$loader->addFixture(new CategoryFixture());
$loader->addFixture(new ArticleFixture());

$purger = new ORMPurger($entityManager);
$purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);

$executor = new ORMExecutor($entityManager, $purger);
$executor->execute($loader->getFixtures());

fwrite(STDOUT, "seed выполнен.\n");
