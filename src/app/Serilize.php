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
            'createdAt' => $category->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $category->getUpdatedAt()->format('Y-m-d H:i:s'),
            'taskCount' => $category->getTasks()->count(),
        ];
    }

    public function task(Task $task): array
    {
        return [
            'id' => $task->getId(),
            'name' => $task->getName(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'createdAt' => $task->getCreatedAt()->format('d/m/y h:i A'),
            'updatedAt' => $task->getUpdatedAt()->format('d/m/y h:i A'),
            'dueDate' => $task->getDueDate()->format('d/m/y h:i A'),
            'category' => $task->getCategory()->getName(),
        ];
    }
}