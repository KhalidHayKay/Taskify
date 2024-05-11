<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Entity\User;

class TaskData
{
    public function __construct(
        public readonly string $name, 
        public readonly string $note, 
        public readonly string $dueDate, 
        public readonly int $categoryId, 
        public readonly User $user,
        public readonly bool $isPriority,
    )
    {
    }
}