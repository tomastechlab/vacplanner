<?php declare(strict_types=1);

namespace App\Enum;

enum PollTypes: string {
    case GENERAL = 'general';
    case DATE = 'date';
    case LOCATION = 'location';
    case OPTION = 'option';
}
