<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\DataTableQueryParams;
use App\DTOs\TaskData;
use App\Serilize;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Category;
use App\Enums\TaskStatusEnum;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
            ->setnote($taskData->note)
            ->setDueDate((new DateTime())->createFromFormat('d/m/Y h:i A', $taskData->dueDate))
            ->setStatus(TaskStatusEnum::Scheduled)
            ->setIsPriority($taskData->isPriority)
            ->setCategory($this->entityManager->find(Category::class, $taskData->categoryId))
            ->setUser($taskData->user);

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

    public function getByStatus(int $userId, TaskStatusEnum $status)
    {
        $tasks = $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t')
            ->where('t.user = :id')->setParameter('id', $userId)
            ->andWhere('t.status = :status')->setParameter('status', $status);

        return new Paginator($tasks);
    }

    public function getForDashboard(int $userId, TaskStatusEnum $status, ?bool $isPriority = null)
    {
        $query = $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t')
            ->where('t.user = :id')->setParameter('id', $userId)
            ->andWhere('t.status = :status')->setParameter('status', $status)
            ->setMaxResults(5)
            ->orderBy('t.dueDate', 'asc');
        
        if (! is_null($isPriority)) {
            $query->andWhere('t.isPriority = :priority')->setParameter('priority', $isPriority);
        }

        $tasks = $query->getQuery()->getResult();
            
        return array_map(fn($task) => $this->serilize->task($task), $tasks);
    }

    public function getPaginated(DataTableQueryParams $params, int $userId): Paginator
    {
        $query = $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t')
            ->where('c.user = :user')->setParameter('user', $userId)
            ->leftJoin('t.category', 'c')
            ->setFirstResult($params->start)
            ->setMaxResults($params->length);

        $orderBy = in_array($params->orderBy, ['name', 'category', 'status', 'createdAt', 'dueDate']) ? $params->orderBy : 'createdAt';
        $orderDir = in_array(strtolower($params->orderDir), ['asc', 'desc', '']) ? $params->orderDir : 'asc';

        if ($orderBy) {
            if ($orderBy === 'category') {
                $query->orderBy('c.name', $orderDir);
            } else {
                $query->orderBy('t.' . $orderBy, $orderDir);
            }
        }

        if (! empty($params->searchTerm)) {
            $query->where('t.name LIKE :name')->setParameter('name', '%' . addcslashes($params->searchTerm, '%_') . '%');
        }

        return new Paginator($query);
    }

    public function delete(int $id): bool
    {
        $task = $this->entityManager->find(Task::class, $id);

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return (bool) $task;
    }

    public function edit(int $id, TaskData $taskData): bool
    {
        $task = $this->entityManager->find(Task::class, $id);

        $task
            ?->setName($taskData->name)
            ?->setnote($taskData->note)
            ?->setDueDate((new DateTime())->createFromFormat('d/m/Y h:i A', $taskData->dueDate))
            ?->setIsPriority($taskData->isPriority)
            ?->setCategory($this->entityManager->find(Category::class, $taskData->categoryId));

        $this->entityManager->flush();

        return (bool) $task;
    }

    public function editPriority(int $id, bool $isPriority): bool
    {
        $task = $this->entityManager->find(Task::class, $id);

        $task?->setIsPriority($isPriority);

        $this->entityManager->flush();

        return (bool) $task;
    }
}