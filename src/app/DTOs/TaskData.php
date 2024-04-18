<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Entity\Category;
use App\Entity\User;
use App\Enums\TaskStatusEnum;
use DateTime;

class TaskData
{
    public function __construct(
        public readonly string $name, 
        public readonly string $description, 
        public readonly string $dueDate, 
        public readonly TaskStatusEnum $status, 
        public readonly int $categoryId, 
        public readonly User $user,
    )
    {   
    }
}