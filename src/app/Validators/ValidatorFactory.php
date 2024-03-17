<?php

declare(strict_types=1);

namespace App\Validators;

use App\Interfaces\ValidatorInterface;
use Psr\Container\ContainerInterface;

class ValidatorFactory
{
    public function __construct(private readonly ContainerInterface $containerInterface)
    {
    }

    public function resolve(string $class): ValidatorInterface
    {
        $validator = $this->containerInterface->get($class);

        if(! $validator instanceof ValidatorInterface) {
            throw new \RuntimeException('Validator Factory cannot resolve class "' . $class . '" because it is not an instace of ValidatiorInterface');
        }

        return $validator;

    }
}