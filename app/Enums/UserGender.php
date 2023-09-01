<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserGender extends Enum
{
    public const MALE = 0;
    public const FEMALE = 1;
    public const OTHER = 2;

    public static function getDescriptions()
    {
        return [
            self::MALE => __('app.male'),
            self::FEMALE => __('app.female'),
            self::OTHER => __('app.others'),
        ];
    }
}
