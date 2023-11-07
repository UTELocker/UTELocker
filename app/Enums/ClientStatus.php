<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ClientStatus extends Enum
{
    const PUBLIC =   1;
    const PRIVATE =   2;
}
