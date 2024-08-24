<?php

declare(strict_types=1);

namespace App\Validators;

use App\Entity\ContactPerson;
use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use App\Interfaces\ValidatorInterface;
use App\Exceptions\InvalidCredentialsException;
use App\Services\TaskProviderService;

class TaskValidator implements ValidatorInterface
{
    public function __construct(private readonly EntityManager $entityManager, private readonly TaskProviderService $taskProvider) {}

    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['name', 'due_date', 'category']);
        $v->rule('lengthMax', 'name', 25);
        $v->rule('lengthMin', 'note', 5);
        $v->rule('lengthMax', 'note', 250);
        $v->rule(
            function () use ($data) {
                if ($data['priority'] === false) {
                    return true;
                } else {
                    return $this->taskProvider->canBePriority($data['user']);
                };
            },
            'priority'
        )->message('You cannot mark task as priority as you do not have a contact person');

        if (! $v->validate()) {
            throw new InvalidCredentialsException($v->errors());
        }

        return $data;
    }
}
