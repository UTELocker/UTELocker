<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\LockerSlot;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class NotificationParentTable extends Enum
{
    const TABLE_BOOKINGS = 'bookings';
    const TABLE_LOCKERS = 'lockers';
    const TABLE_TRANSACTIONS = 'transactions';

    public static function queryTable($table, $parentId)
    {
        switch ($table) {
            case self::TABLE_BOOKINGS:
                $record = Booking::where('bookings.id', $parentId)
                    ->leftJoin('locker_slots', 'locker_slots.id', '=', 'bookings.locker_slot_id')
                    ->leftJoin('lockers', 'lockers.id', '=', 'locker_slots.locker_id')
                    ->leftJoin('locations', 'locations.id', '=', 'lockers.location_id')
                    ->select('bookings.status as booking_status', 'bookings.id as booking_id',
                        'bookings.start_date', 'bookings.end_date', 'bookings.created_at',
                        'lockers.code as locker_code', 'lockers.id as locker_id',
                        'locker_slots.id as locker_slot_id',
                        'locations.description as address', 'locations.longitude', 'locations.latitude')
                    ->first();
                $codeSlotLocker = LockerSlot::getCode(
                    $record->locker_id,
                    $record->locker_slot_id
                );
                $record->locker_slot_code = $codeSlotLocker;
                return $record;
            case self::TABLE_LOCKERS:
            case self::TABLE_TRANSACTIONS:
            default:
                if (Schema::hasTable($table)) {
                    return  DB::table($table)
                        ->where('id', $parentId)
                        ->first();
                    }
                return null;
        }
    }
}
