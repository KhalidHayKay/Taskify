<?php

declare(strict_types=1);

namespace App\Interfaces;

interface UserInterface
{
    public function getId(): int;

    public function getPassword(): string;

    public function getUsername(): string;
    public function setUsername(string $username): self;

    public function getFullname(): string;
    public function setFullname(string $fullname): self;

    public function getEmail(): string;
    public function setEmail(string $email): self;

    public function getDateOfBirth(): ?string;
    public function setDateOfBirth(?string $email): self;
}