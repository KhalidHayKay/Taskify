<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAt;
use App\Entity\Traits\UpdatedAt;
use App\Enums\TaskStatusEnum;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity()]
#[Table('tasks')]
class Task
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column]
    private ?string $description;

    #[Column(name: 'created_at')]
    private DateTime $createdAt;

    #[Column(name: 'updated_at')]
    private DateTime $updatedAt;

    #[Column(name: 'due_date')]
    private DateTime $dueDate;

    #[Column]
    private TaskStatusEnum $status;

    #[ManyToOne(inversedBy: 'tasks')]
    private User $user;

    #[ManyToOne(inversedBy: 'tasks')]
    private Category $category;

	use CreatedAt;
	use UpdatedAt;

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;
		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): self
	{
		$this->description = $description;
		return $this;
	}

	public function getCreatedAt(): DateTime
	{
		return $this->createdAt;
	}

	public function getUpdatedAt(): DateTime
	{
		return $this->updatedAt;
	}

	public function getDueDate(): DateTime
	{
		return $this->dueDate;
	}

	public function setDueDate(DateTime $dueDate): self
	{
		$this->dueDate = $dueDate;
		return $this;
	}

	public function getStatus(): TaskStatusEnum
	{
		return $this->status;
	}

	public function setStatus(TaskStatusEnum $status): self
	{
		$this->status = $status;
		return $this;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user): self
	{
		$this->user = $user;
		return $this;
	}

	public function getCategory(): Category
	{
		return $this->category;
	}

	public function setCategory(Category $category): self
	{
		$this->category = $category;
		return $this;
	}
}