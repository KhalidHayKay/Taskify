<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionSamesiteOptionEnum: string
{
    case None = 'none';
    case Lax = 'lax';
    case Strict = 'strict';
}