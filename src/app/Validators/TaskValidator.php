<?php

declare(strict_types=1);

namespace App\Validators;

use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use App\Interfaces\ValidatorInterface;
use App\Exceptions\InvalidCredentialsException;

class TaskValidator implements ValidatorInterface
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['name', 'due_date', 'category']);

        /* $v->rule(
            function () use ($data) {
                $count = $this->entityManager->getRepository(Category::class)->count([
                    'user' => $this->entityManager->find(User::class, $data['user_id']), 
                    'name' => $data['name']
                ]);

                $name = $this->entityManager->getRepository(Category::class)->findOneBy([
                    'user' => $this->entityManager->find(User::class, $data['user_id']), 
                    'name' => $data['name']
                ]);

                $name = $name ? $name->getName() : '';

                if (isset($data['isEdit']) && $data['isEdit'] === true) {
                    // var_dump($name);
                    return $name !== $data['name'];
                }

                return ! $count;
            },
            'name'
        )->message('A category with the given name already exists'); */

        $v->rule('lengthMax', 'name', 25);
        $v->rule('lengthMin', 'note', 5);
        $v->rule('lengthMax', 'note', 50);

        if (! $v->validate()) {
            throw new InvalidCredentialsException($v->errors());
        }

        return $data;
    }
}
