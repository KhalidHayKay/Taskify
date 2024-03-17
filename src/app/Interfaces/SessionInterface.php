<?php

declare(strict_types=1);

namespace App\Interfaces;

interface SessionInterface
{
    public function start(): void;

    public function save(): void;

    public function isActive(): bool;

    public function put(string $key, mixed $value): void;
    
    public function get(string $key): mixed;

    public function remove(string $key): void;

    public function regenerate(): void;

    public function flash(string $key, mixed $value): void;

    public function getFlash(string $key): mixed;
}