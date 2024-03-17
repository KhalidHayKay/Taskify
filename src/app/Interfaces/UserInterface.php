<?php

declare(strict_types=1);

namespace App\Interfaces;

interface UserInterface
{
    public function getId(): int;

    public function getPassword(): string;

    public function getUsername(): string;
}