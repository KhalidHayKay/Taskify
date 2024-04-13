<?php

declare(strict_types=1);

namespace App;

use App\Entity\Category;

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
}