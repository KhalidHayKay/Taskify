<?php

declare(strict_types=1);

namespace App\DTOs;

class UserRegData
{
    public function __construct
    (
        public readonly string $fullname,
        public readonly string $username,
        public readonly string $email,
        public readonly string $password,
    )
    {
    }
}