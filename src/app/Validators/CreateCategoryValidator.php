<?php

declare(strict_types=1);

namespace App\Validators;

use App\Entity\Category;
use App\Entity\User;
use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use App\Interfaces\ValidatorInterface;
use App\Exceptions\InvalidCredentialsException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateCategoryValidator implements ValidatorInterface
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['name']);

        $v->rule(
            function () use ($data) {
                return ! $this->entityManager->getRepository(Category::class)->count([
                    'user' => $data['user'],
                    'name' => $data['name']
                ]);
            },
            'name'
        )->message('A category with the given name already exists');

        $v->rule('lengthMax', 'name', 25);

        if (! $v->validate()) {
            throw new InvalidCredentialsException($v->errors());
        }

        return $data;
    }
}
