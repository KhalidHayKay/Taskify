<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAt;
use App\Interfaces\UserInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Table;

#[Entity()]
#[Table('users')]
#[HasLifecycleCallbacks]
class User implements UserInterface
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue()]
    private int $id;

    #[Column(name: 'full_name')]
    private string $fullName;

    #[Column(unique: true)]
    private string $username;

    #[Column(unique: true)]
    private string $email;

    #[Column]
    private string $password;

    #[Column(name: 'created_at')]
    private DateTime $createdAt;

    #[OneToOne(inversedBy: 'user')]
	#[JoinColumn(name: 'contact_person_id')]
    private ?ContactPerson $contactPerson;

    #[OneToMany(targetEntity: Category::class, mappedBy: 'user')]
    private Collection $categories;

    #[OneToMany(targetEntity: Task::class, mappedBy: 'user')]
    private Collection $tasks;

	use CreatedAt;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

	public function getId(): int
	{
		return $this->id;
	}

	public function getFullName(): string
	{
		return $this->fullName;
	}

	public function setFullName(string $fullName): self
	{
		$this->fullName = $fullName;
		return $this;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function setUsername(string $username): self
	{
		$this->username = $username;
		return $this;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;
		return $this;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;
		return $this;
	}

	public function getCreatedAt(): DateTime
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTime $createdAt): self
	{
		$this->createdAt = $createdAt;
		return $this;
	}

	public function getContactPerson(): ?ContactPerson
	{
		return $this->contactPerson;
	}

	public function setContactPerson(?ContactPerson $contactPerson): self
	{
		$this->contactPerson = $contactPerson;
		return $this;
	}

	public function getTasks(): Collection
	{
		return $this->tasks;
	}

    public function addTask(Task $task)
    {
        $task->setUser($this);

        $this->tasks->add($task);

        return $this;
    }

	public function getCategories(): Collection
	{
		return $this->categories;
	}

    public function addCategory(Category $category)
    {
        $category->setUser($this);
        
        $this->tasks->add($category);

        return $this;
    }
}