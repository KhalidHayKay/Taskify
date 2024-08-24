<?php

declare(strict_types=1);

namespace App;

use App\Entity\Category;
use App\Entity\Task;

class Serilize
{
    public function category(Category $category): array
    {
        return [
            'id'        => $category->getId(),
            'name'      => $category->getName(),
            'createdAt' => $category->getCreatedAt()->format('d/m/Y h:i A'),
            'updatedAt' => $category->getUpdatedAt()->format('d/m/Y h:i A'),
            'taskCount' => $category->getTasks()->count(),
        ];
    }

    public function task(Task $task): array
    {
        return [
            'id'         => $task->getId(),
            'name'       => $task->getName(),
            'note'       => $task->getnote(),
            'status'     => $task->getStatus(),
            'isPriority' => $task->getIsPriority(),
            'createdAt'  => $task->getCreatedAt()->format('d/m/Y h:i A'),
            'updatedAt'  => $task->getUpdatedAt()->format('d/m/Y h:i A'),
            'dueDate'    => $task->getDueDate()->format('d/m/Y h:i A'),
            'category'   => $task->getCategory()->getName(),
        ];
    }
}