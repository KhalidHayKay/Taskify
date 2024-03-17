<?php

declare(strict_types=1);

namespace App\Validators;

use App\Entity\User;
use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use App\Interfaces\ValidatorInterface;
use App\Exceptions\InvalidCredentialsException;

class SignUpValidator implements ValidatorInterface
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }
    
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['fullname', 'username', 'email', 'password', 'confirm_password']);
        $v->rule(
            fn() => ! $this->entityManager->getRepository(User::class)->count(['username' => $data['username']]),
            'username'
        )->message('Username has been chosen, try another!');
        $v->rule('email', 'email');
        $v->rule(
            fn() => ! $this->entityManager->getRepository(User::class)->count(['email' => $data['email']]),
            'email'
        )->message('A user with given email already exists');
        $v->rule('lengthMin', 'password', 6);
        $v->rule('equals', 'confirm_password', 'password');

        if(! $v->validate()) {
            throw new InvalidCredentialsException($v->errors());
        }

        return $data;
    }
}