<?php

namespace App\Models;

use App\Classes\CommonConstant;
use App\Traits\HasSiteGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Znck\Eloquent\Traits\BelongsToThrough;

class Booking extends Model
{
    use HasSiteGroup;
    use BelongsToThrough;
    public function lockerSlot(): BelongsTo
    {
        return $this->belongsTo(LockerSlot::class);
    }

    public function locker()
    {
        return $this->belongsToThrough(Locker::class, LockerSlot::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class , 'transaction_id');
    }

    public function scopeFullDetails($query)
    {
        return $query
                ->leftJoin('locker_slots', 'bookings.locker_slot_id', '=', 'locker_slots.id')
                ->leftJoin('lockers', 'locker_slots.locker_id', '=', 'lockers.id')
                ->leftJoin('locations', 'lockers.location_id', '=', 'locations.id')
                ->leftJoin('transactions', 'bookings.transaction_id', '=', 'transactions.id')
                ->leftJoin('users', 'bookings.owner_id', '=', 'users.id')
                ->leftJoin('clients', 'bookings.client_id', '=', 'clients.id')
                ->select(
                    'bookings.status', 'bookings.start_date',
                    'bookings.end_date', 'bookings.created_at', 'bookings.id', 'bookings.updated_at',
                    'locker_slots.row', 'locker_slots.column', 'locker_slots.locker_id',
                    'locker_slots.id as locker_slot_id',
                    'locker_slots.config', 'lockers.image', 'lockers.code as locker_code',
                    'locations.description as address', 'locations.latitude', 'locations.longitude',
                    'transactions.amount as total_price', 'transactions.id as transaction_id',
                    'transactions.reference as transaction_reference',
                    'users.name as owner_name', 'users.mobile as owner_phone', 'users.email as owner_email',
                    'clients.name as client_name'
                )
                ->orderBy('bookings.start_date', 'asc');
    }
}
