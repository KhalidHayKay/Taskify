<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatusEnum: string
{
    case Scheduled = 'scheduled';
    case Completed = 'completed';
    case OverDue = 'overdue';
}