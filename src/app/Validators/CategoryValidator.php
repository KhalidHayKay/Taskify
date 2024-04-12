<?php

declare(strict_types=1);

namespace App\Validators;

use App\Entity\User;
use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use App\Interfaces\ValidatorInterface;
use App\Exceptions\InvalidCredentialsException;

class CategoryValidator implements ValidatorInterface
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }
    
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['name']);
        // $v->rule(
        //     fn() => ! $this->entityManager->getRepository(User::class)->count(['username' => $data['username']]),
        //     'name'
        // )->message('A category with the given name already exists');
        $v->rule('lengthMin', 'password', 5);
        $v->rule('lengthMax', 'password', 25);

        if(! $v->validate()) {
            throw new InvalidCredentialsException($v->errors());
        }

        return $data;
    }
}