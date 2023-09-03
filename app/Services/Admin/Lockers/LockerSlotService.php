<?php

namespace App\Services\Admin\Lockers;

use App\Models\LockerSlot;
use App\Services\BaseService;

class LockerSlotService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new LockerSlot());
    }
}
