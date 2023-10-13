<?php

namespace App\Models;

use App\Enums\LockerSlotType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LockerSlot extends Model
{
    public function locker(): BelongsTo
    {
        return $this->belongsTo(Locker::class);
    }

    public static function getListNameSlots($listSlotS)
    {
        $numberSlot = 1;
        $listNameSlots = [];
        foreach ($listSlotS as $slot) {
            if ($slot->type === LockerSlotType::SLOT) {
                $listNameSlots[$slot->row . '-' . $slot->column] = $numberSlot;
                $numberSlot++;
            }
        }
        return $listNameSlots;
    }
}
