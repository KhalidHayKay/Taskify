<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAt;
use App\Entity\Traits\UpdatedAt;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('categories')]
#[HasLifecycleCallbacks]
class Category
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column(name: 'created_at')]
    private DateTime $createdAt;

    #[Column(name: 'updated_at')]
    private DateTime $updatedAt;

    #[ManyToOne(inversedBy: 'categories')]
    private User $user;

    #[OneToMany(targetEntity: Task::class, mappedBy: 'category')]
    private Collection $tasks;

	use CreatedAt;
	use UpdatedAt;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

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

	public function getCreatedAt(): DateTime
	{
		return $this->createdAt;
	}

	public function getUpdatedAt(): DateTime
	{
		return $this->updatedAt;
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

	public function getTasks(): Collection
	{
		return $this->tasks;
	}

	public function addTask(Task $task): self
	{
        $task->setCategory($this);

		$this->tasks->add($task);

		return $this;
	}
}