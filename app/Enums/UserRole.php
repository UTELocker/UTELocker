<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRole extends Enum
{
    public const SUPER_USER = 0;
    public const ADMIN = 1;
    public const NORMAL = 2;

    public static function getDescription(int $value): string
    {
        return self::getDescriptions()[$value];
    }

    public static function getDescriptions(): array
    {
        return [
            self::SUPER_USER => __('app.superUser'),
            self::ADMIN => __('app.admin'),
            self::NORMAL => __('app.user'),
        ];
    }
}
