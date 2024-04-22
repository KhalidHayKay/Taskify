<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Category;
use App\Entity\User;
use App\Serilize;
use Doctrine\ORM\EntityManager;

class CategoryProviderService
{
    public function __construct(private readonly EntityManager $entityManager, private readonly Serilize $serilize)
    {
    }
    
    public function create(string $name, User $user): array
    {
        $category = new Category;

        $category->setName($name)->setUser($user);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->serilize->category($category);
    }

    public function getAll(User $user): array
    {
        $categories = $this->entityManager->find(User::class, $user->getId())->getCategories()->toArray();

        return array_map(fn($category) => $this->serilize->category($category), $categories);
    }

    public function get(int $id): array
    {
        $category = $this->entityManager->find(Category::class, $id);

        return $this->serilize->category($category);
    }

    public function delete(int $id): void
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