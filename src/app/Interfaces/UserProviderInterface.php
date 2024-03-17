<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTOs\UserLoginData;
use App\DTOs\UserRegData;

interface UserProviderInterface
{
    public function create(UserRegData $data): UserInterface;

    public function findByCredentials(UserLoginData $data): ?UserInterface;

    public function findById(int $id): ?UserInterface;
}