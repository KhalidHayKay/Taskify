<?php

declare(strict_types=1);

namespace App;

use App\DTOs\UserLoginData;
use App\DTOs\UserRegData;
use App\Entity\User;
use App\Interfaces\AuthInterface;
use App\Interfaces\SessionInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\UserProviderInterface;

class Auth implements AuthInterface
{
    private ?User $user = null;

    public function __construct(private readonly UserProviderInterface $userProvider, private readonly SessionInterface $session)
    {   
    }

    public function user(): ?UserInterface
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $userId = $this->session->get('user');

        if (! $userId) {
            return null;
        }

        $user = $this->userProvider->findById($userId);

        if(! $user) {
            return null;
        }

        $this->user = $user;

        return $this->user;
    }

    public function register(UserRegData $data): UserInterface
    {
        $user = $this->userProvider->create($data);

        $this->login($user);

        return $user;
    }

    public function attemptLogin(UserLoginData $data): bool
    {
        $user = $this->userProvider->findByCredentials($data);

        if (! $user || ! $this->verifyCredentials($user, $data)) {
            return false;
        }

        $this->login($user);

        return true;
    }

    public function login(UserInterface $user): void
    {
        $this->session->regenerate();
        $this->session->put('user', $user->getId());

        $this->user = $user;
    }

    public function logout(): void
    {
        $this->session->regenerate();
        $this->session->remove('user');

        $this->user = null;
    }

    public function verifyCredentials(UserInterface $user, UserLoginData $data): bool
    {
        return password_verify($data->password, $user->getPassword());
    }
}