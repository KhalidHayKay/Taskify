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

        $task
            ->setName($taskData->name)
            ->setDescription($taskData->description)
            ->setDueDate(new DateTime($taskData->dueDate))
            ->setStatus($taskData->status)
            ->setCategory($this->entityManager->find(Category::class, $taskData->categoryId))
            ->setUser($taskData->user)
        ;

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->serilize->task($task);
    }

    public function getAll(User $user): array
    {
        $tasks = $this->entityManager->find(User::class, $user->getId())->getTasks()->toArray();

        return array_map(fn($task) => $this->serilize->task($task), $tasks);
    }

    public function get(int $id): array
    {
        $task = $this->entityManager->find(Task::class, $id);

        return $this->serilize->task($task);
    }

    public function delete(int $id): void
    {
        $task = $this->entityManager->find(Task::class, $id);

        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function edit(int $id, TaskData $taskData)
    {
        $task = $this->entityManager->find(Task::class, $id);

        $task
            ->setName($taskData->name)
            ->setDescription($taskData->description)
            ->setDueDate($taskData->dueDate)
            ->setCategory($this->entityManager->find(Category::class, $taskData->categoryId))
        ;

        $this->entityManager->flush();
    }
}