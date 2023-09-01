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
        return self::getDescriptions()[$value];
    }

    public static function getDescriptions($exclude = []): array
    {
        $descriptions = [
            self::SUPER_USER => 'Super User',
            self::ADMIN => 'Admin',
            self::NORMAL => 'Normal',
        ];

        if (count($exclude) > 0) {
            foreach ($exclude as $value) {
                unset($descriptions[$value]);
            }
        }

        return $descriptions;
    }
}
