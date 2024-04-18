<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\TaskData;
use App\Serilize;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Category;
use App\Enums\TaskStatusEnum;
use DateTime;
use Doctrine\ORM\EntityManager;

class TaskProviderService
{
    public function __construct(private readonly EntityManager $entityManager, private readonly Serilize $serilize)
    {
    }
    
    public function create(TaskData $taskData): array
    {
        $task = new Task;

        $task->setName($taskData->name);
        $task->setDescription($taskData->description);
        $task->setDueDate(new DateTime($taskData->dueDate));
        $task->setStatus($taskData->status);
        $task->setCategory($this->entityManager->find(Category::class, $taskData->categoryId));
        $task->setUser($taskData->user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->serilize->task($task);
    }

    public function getAll(User $user): array
    {
        $categories = $this->entityManager->find(User::class, $user->getId())->getTasks()->toArray();

        return array_map(function($category) {
           return $this->serilize->category($category);
        }, $categories);
    }

    public function get(int $id): array
    {
        $category = $this->entityManager->find(Category::class, $id);

        return $this->serilize->category($category);
    }

    public function delete(int $id): void
    {
        $category = $this->entityManager->find(Task::class, $id);

        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    public function edit(int $id, TaskData $taskData)
    {
        $task = $this->entityManager->find(Task::class, $id);

        $task->setName($taskData->name);
        $task->setDescription($taskData->description);
        $task->setDueDate($taskData->dueDate);
        $task->setCategory($this->entityManager->find(Category::class, $taskData->categoryId));

        $this->entityManager->flush();
    }
}