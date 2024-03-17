<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatusEnum: string
{
    case CommingUp = 'comming up';
    case Completed = 'completed';
    case OverDue = 'over due';
}