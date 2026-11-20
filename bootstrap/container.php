<?php

declare(strict_types=1);

use App\Article\Infrastructure\DI\ArticleServiceProvider;
use App\Category\Infrastructure\DI\CategoryServiceProvider;
use App\Home\Infrastructure\DI\HomeServiceProvider;
use App\Shared\Infrastructure\DI\DoctrineServiceProvider;
use App\Shared\Infrastructure\DI\SmartyServiceProvider;
use League\Container\Container;

$projectRoot = dirname(__DIR__);
$config = require $projectRoot . '/bootstrap/config.php';
$isDevMode = $config['is_dev_mode'];
$databaseConfig = $config['database'];
$container = new Container();

$container->addServiceProvider(
    new DoctrineServiceProvider($databaseConfig, $isDevMode, $projectRoot),
);
$container->addServiceProvider(new SmartyServiceProvider($projectRoot));
$container->addServiceProvider(new HomeServiceProvider());
$container->addServiceProvider(new CategoryServiceProvider());
$container->addServiceProvider(new ArticleServiceProvider());

return $container;
