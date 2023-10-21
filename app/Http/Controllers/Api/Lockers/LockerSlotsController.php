<?php

namespace App\Http\Controllers\Api\Lockers;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Services\Admin\Lockers\LockerSlotService;
use Illuminate\Http\Request;

class LockerSlotsController extends Controller
{
    protected ?LockerSlotService $lockerSlotService;

    public function __construct(LockerSlotService $lockerSlotService)
    {
        $this->lockerSlotService = $lockerSlotService;
    }

}
