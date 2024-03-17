<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTOs\UserLoginData;
use App\DTOs\UserRegData;

interface AuthInterface
{
    public function user(): ?UserInterface;

    public function register(UserRegData $data): UserInterface;

    public function attemptLogin(UserLoginData $data): bool;

    public function login(UserInterface $user): void;

    public function logout(): void;

    public function verifyCredentials(UserInterface $user, UserLoginData $credentials): bool;
}