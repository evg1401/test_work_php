<?php

declare(strict_types=1);

use App\Application;

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');

$projectRoot = dirname(__DIR__);
$autoloadPath = $projectRoot . '/vendor/autoload.php';

if (!is_file($autoloadPath)) {
    throw new RuntimeException(
        'Composer autoloader not found. Run "composer install" in the project root.',
    );
}

require_once $autoloadPath;

return new Application();
