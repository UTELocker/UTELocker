<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BookingStatus extends Enum
{
    public const PENDING = 0;
    public const APPROVED = 1;
    public const REJECTED = 2;
    public const CANCELLED = 3;
    public const EXPIRED = 4;
    public const COMPLETED = 5;
    public const LOCKED = 6;

    public static function getDescriptions(): array
    {
        return [
            self::PENDING => __('app.enums.bookingStatus.pending'),
            self::APPROVED => __('app.enums.bookingStatus.approved'),
            self::REJECTED => __('app.enums.bookingStatus.rejected'),
            self::CANCELLED => __('app.enums.bookingStatus.cancelled'),
            self::EXPIRED => __('app.enums.bookingStatus.expired'),
            self::COMPLETED => __('app.enums.bookingStatus.completed'),
            self::LOCKED => __('app.enums.bookingStatus.locked'),
        ];
    }
}
