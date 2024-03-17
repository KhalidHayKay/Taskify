<?php

declare(strict_types=1);

namespace App\Validators;

use Valitron\Validator;
use App\Interfaces\ValidatorInterface;
use App\Exceptions\InvalidCredentialsException;

class LoginValidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['email', 'password']);
        $v->rule('email', 'email');

        if (! $v->validate()) {
            throw new InvalidCredentialsException($v->errors());
        }

        return $data;
    }
}