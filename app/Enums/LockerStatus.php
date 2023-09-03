<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LockerStatus extends Enum
{
    public const AVAILABLE = 0;
    public const IN_USE = 1;
    public const UNDER_MAINTENANCE = 2;
    public const BROKEN = 3;

    public static function getDescriptions(array $exclude = []): array
    {
        $descriptions = [
            self::AVAILABLE => __('app.enums.lockerStatus.available'),
            self::IN_USE => __('app.enums.lockerStatus.inUse'),
            self::UNDER_MAINTENANCE => __('app.enums.lockerStatus.underMaintenance'),
            self::BROKEN => __('app.enums.lockerStatus.broken'),
        ];

        if (!empty($exclude)) {
            foreach ($exclude as $key) {
                unset($descriptions[$key]);
            }
        }

        return $descriptions;
    }
}
