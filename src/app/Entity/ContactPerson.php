<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

#[Entity()]
#[Table(name: 'contact_persons')]
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

    #[OneToOne(inversedBy: 'contactPerson')]
	#[JoinColumn(name: 'user_id')]
    private User $user;

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
}