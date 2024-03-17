<?php

declare(strict_types=1);

namespace App;

class Config
{
    private array $configs = [];

    public function __construct(private array $env)
    {
        $this->configs = [
            'app_name' => $env['APP_NAME'],
            'app_version' => $env['APP_VERSION'],
            'app_debug' => (bool) $env['APP_DEBUG'],
            'app_environment' => $env['APP_ENV'],
            'db' => [
                'driver'   => $env['driver'] ?? 'pdo_mysql',
                'user'     => $env['DB_USER'],
                'password' => $env['DB_PASS'],
                'dbname'   => $env['DB_NAME'],
            ],
            'session' => [
                'name' => 'taskify_session',
                'secure' => false,
                'httpOnly' => true,
                'sameSite' => 'lax'
            ],
        ];
    }

    public function get(string $name): mixed
    {
        return $this->configs[$name];
    }
}