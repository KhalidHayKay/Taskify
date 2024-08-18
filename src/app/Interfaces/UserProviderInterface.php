<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTOs\UserRegData;
use App\DTOs\UserLoginData;

interface UserProviderInterface
{
    public function create(UserRegData $data): UserInterface;

    public function findByCredentials(UserLoginData $data): ?UserInterface;

    public function findById(int $id): ?UserInterface;
}