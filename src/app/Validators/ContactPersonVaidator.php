<?php

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\InvalidContactPersonDataException;
use Valitron\Validator;
use App\Interfaces\ValidatorInterface;
use App\Exceptions\InvalidCredentialsException;

class ContactPersonVaidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['fullname', 'email']);
        $v->rule('email', 'email');

        if (! $v->validate()) {
            throw new InvalidContactPersonDataException($v->errors());
        }

        return $data;
    }
}