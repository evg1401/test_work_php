<?php

declare(strict_types=1);

use App\Application;

/** @var Application $application */
$application = require dirname(__DIR__) . '/bootstrap/app.php';
$application->run();
