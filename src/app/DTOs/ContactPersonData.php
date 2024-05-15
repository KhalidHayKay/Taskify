<?php

declare(strict_types=1);

namespace App\DTOs;

class ContactPersonData
{
    public function __construct(
        public readonly string $email, 
        public readonly string $fullname
    )
    {
    }
}