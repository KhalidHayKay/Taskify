<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

class InvalidContactPersonDataException extends \RuntimeException
{
    public function __construct(
        public readonly array $errors,
        string $message = 'Invalid contact person data',
        int $code = 422,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
