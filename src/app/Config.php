<?php

declare(strict_types=1);

namespace App;

class Config
{
    public function __construct(private readonly array $configs)
    {
    }

    public function get(string $name, mixed $default = null): mixed
    {
        $keys = explode('.', $name);
        $value = $this->configs;

        foreach ($keys as $nestedKey) {
            if (isset($value[$nestedKey])) {
                $value = $value[$nestedKey];
            } else {
                return $default;
            }
        }

        return $value;
    }
}