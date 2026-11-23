<?php

declare(strict_types=1);

$getEnv = static function (string $name): string {
    $value = getenv($name);

    if ($value === false || $value === '') {
        throw new RuntimeException(sprintf('Переменная окружения "%s" не установлена.', $name));
    }

    return $value;
};

$isDevMode = filter_var($getEnv('APP_DEV'), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

if ($isDevMode === null) {
    throw new RuntimeException('Переменная "APP_DEV" не соответствует типу bool.');
}

$databaseConfig = [
    'driver' => $getEnv('DB_DRIVER'),
    'host' => $getEnv('DB_HOST'),
    'port' => (int) $getEnv('DB_PORT'),
    'dbname' => $getEnv('DB_NAME'),
    'user' => $getEnv('DB_USER'),
    'password' => $getEnv('DB_PASSWORD'),
    'charset' => $getEnv('DB_CHARSET'),
];

return [
    'is_dev_mode' => $isDevMode,
    'database' => $databaseConfig,
];
