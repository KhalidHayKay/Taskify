<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\PrePersist;

#[Entity()]
#[Table(name: 'contact_persons')]
#[HasLifecycleCallbacks()]
class ContactPerson
{
	#[Id, Column(options: ['unsigned' => true]), GeneratedValue()]
	private int $id;

	#[Column(unique: true)]
	private string $email;

	#[Column(name: 'full_name')]
	private string $fullname;

	#[Column(name: 'is_user')]
	private bool $isUser;

	#[Column('has_accepted')]
	private bool $hasAccepted;

	#[Column('is_acknowledged')]
	private bool $isAcknowledged;

	#[OneToOne(inversedBy: 'contactPerson')]
	#[JoinColumn(name: 'user_id')]
	private User $user;

	#[PrePersist()]
	public function setHasAcceptedAndIsAcknowlegded()
	{
		$this->hasAccepted    = false;
		$this->isAcknowledged = false;
	}

	public function getId(): int
	{
		return $this->id;
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

	public function getFullname(): string
	{
		return $this->fullname;
	}

	public function setFullname(string $fullname): self
	{
		$this->fullname = $fullname;
		return $this;
	}

	public function getIsUser(): bool
	{
		return $this->isUser;
	}

	public function setIsUser(bool $isUser): self
	{
		$this->isUser = $isUser;
		return $this;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user): self
	{
		$user->addContactPerson($this);

		$this->user = $user;

		return $this;
	}
	public function getHasAccepted(): bool
	{
		return $this->hasAccepted;
	}

	public function setHasAccepted(bool $hasAccepted): self
	{
		$this->hasAccepted = $hasAccepted;
		return $this;
	}

	public function getIsAcknowledged(): bool
	{
		return $this->isAcknowledged;
	}

	public function setIsAcknowledged(bool $isAcknowledged): self
	{
		$this->isAcknowledged = $isAcknowledged;
		return $this;
	}
}