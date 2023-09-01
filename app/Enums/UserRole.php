<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRole extends Enum
{
    public const SUPER_USER = 0;
    public const ADMIN = 1;
    public const NORMAL = 2;

    public static function getDescription($value): string
    {
        if ($value === self::SUPER_USER) {
            return 'Super User';
        }
        if ($value === self::ADMIN) {
            return 'Admin User';
        }
        if ($value === self::NORMAL) {
            return 'Normal User';
        }
        return parent::getDescription($value);
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
