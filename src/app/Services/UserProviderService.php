<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\DTOs\UserRegData;
use App\DTOs\UserLoginData;
use App\DTOs\UserUpdateData;
use Doctrine\ORM\EntityManager;
use App\Interfaces\UserInterface;
use App\Interfaces\UserProviderInterface;

class UserProviderService implements UserProviderInterface 
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function create(UserRegData $data): UserInterface
    {
        $user = new User;

        $user
            ->setFullname($data->fullname)
            ->setUsername($data->username)
            ->setEmail($data->email)
            ->setPassword(password_hash($data->password, PASSWORD_BCRYPT, ['cost' => 12]));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function findById(int $id): ?UserInterface
    {
        return $this->entityManager->find(User::class, $id);
    }

    public function findByCredentials(UserLoginData $data): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data->email]);
    }
}