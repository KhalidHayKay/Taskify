<?php

declare(strict_types=1);

namespace App\DTOs;

class UserLoginData
{
    public function __construct
    (
        public readonly string $email,
        public readonly string $password,
    )
    {
    }
}