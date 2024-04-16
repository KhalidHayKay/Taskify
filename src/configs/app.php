<?php

use App\Enums\AppEnvironmentEnum;

return [
    'app_name' => $_ENV['APP_NAME'],
    'app_version' => $_ENV['APP_VERSION'],
    'app_debug' => (bool) $_ENV['APP_DEBUG'],
    'app_environment' => $_ENV['APP_ENV'],
    'doctrine' => [
        'dev_mode' => AppEnvironmentEnum::isDevelopment($_ENV['APP_ENV'] ?? AppEnvironmentEnum::Development->value),
        'cache_dir'  => STORAGE_PATH . '/cache/doctrine',
        'entity_dir' => [
            APP_PATH . '/Entity',
        ],
        'connection' => [
            'driver'   => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
            'host'     => $_ENV['DB_HOST'] ?? 'localhost', 
            'port'     => $_ENV['DB_PORT'] ?? 3306,
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'dbname'   => $_ENV['DB_NAME'],
        ],
    ],
    'session' => [
        'name' => 'taskify_session',
        'secure' => true,
        'httpOnly' => true,
        'sameSite' => 'lax'
    ],
];