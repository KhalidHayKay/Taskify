<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ValidatorInterface
{
    public function validate(array $data): array;
}