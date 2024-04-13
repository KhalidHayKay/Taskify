<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class CategoryProviderService
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }
    
    public function create(string $name, User $user): array
    {
        $category = new Category;

        $category->setName($name);
        $category->setUser($user);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->getAll($user);
    }

    public function getAll(User $user): array
    {
        $data = $this->entityManager->find(User::class, $user->getId())->getCategories()->toArray();
        $categories = [];

        foreach ($data as $category) {
            $categories[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'createdAt' => $category->getCreatedAt(),
                'updatedAt' => $category->getUpdatedAt(),
                'taskCount' => $category->getTasks()->count(),
            ];
        }

        return $categories;
    }

    public function delete(int $id)
    {
        $category = $this->entityManager->find(Category::class, $id);

        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    public function edit(int $id, string $name)
    {
        $category = $this->entityManager->find(Category::class, $id);

        $category->setName($name);

        $this->entityManager->flush();
    }
}