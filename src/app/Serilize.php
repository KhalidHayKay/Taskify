<?php

declare(strict_types=1);

namespace App;

use App\Entity\Category;
use App\Entity\Task;

class  Serilize
{
    public function category(Category $category): array
    {
        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'createdAt' => $category->getCreatedAt(),
            'updatedAt' => $category->getUpdatedAt(),
            'taskCount' => $category->getTasks()->count(),
        ];
    }

    public function task(Task $task): array
    {
        return [
            'id' => $task->getId(),
            'id' => $task->getName(),
            'id' => $task->getDescription(),
            'id' => $task->getStatus(),
            'id' => $task->getCreatedAt(),
            'id' => $task->getUpdatedAt(),
            'id' => $task->getDueDate(),
            'id' => $task->getCategory(),
        ];
    }
}