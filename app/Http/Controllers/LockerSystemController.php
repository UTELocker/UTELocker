<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HandleNotification;
use App\Enums\NotificationType;
use App\Services\Admin\Lockers\LockerSlotService;
use App\Services\Admin\Bookings\BookingService;
use App\Services\LockeSystem\LockerSystemLogService;
use App\Services\Admin\Licenses\LicenseService;

class LockerSystemController extends Controller
{
    protected ?LockerSlotService $lockerSlotService;
    protected ?BookingService $bookingService;
    protected ?LockerSystemLogService $lockerSystemLogService;
    protected ?LicenseService $licenseService;

    use HandleNotification;

    public function __construct(
        LockerSlotService $lockerSlotService,
        BookingService $bookingService,
        LockerSystemLogService $lockerSystemLogService,
        LicenseService $licenseService
    ) {
        $this->lockerSlotService = $lockerSlotService;
        $this->bookingService = $bookingService;
        $this->lockerSystemLogService = $lockerSystemLogService;
        $this->licenseService = $licenseService;
    }

    public function systemPassword (Request $request)
    {
        $lockerSlot = $this->lockerSlotService->getLockerSlotByPassword(
            $request->password,
            $request->header('X-License-Id')
        );
        if (!$lockerSlot) {
            return Reply::error('Password is invalid');
        }

        $this->sendNotification(
            NotificationType::LOCKER_SYSTEM,
            'Ngăn tủ tại ' . $lockerSlot->address . ' đã được mở',
            $lockerSlot->owner_id,
            $lockerSlot->client_id,
            'bookings',
            $lockerSlot->booking_id
        );

        return Reply::successWithData('Locker slot found', [
            'data' => $lockerSlot,
        ]);
    }

    public function syn(Request $request)
    {
        $bookings = $this->bookingService->getBookingActivitiesLicense(
            $request->header('X-License-Id'),
        );

        return Reply::successWithData('Syn successfully',
            [
                'data' => $bookings
            ]
        );
    }

    public function logActive(Request $request)
    {
        $data = $request->all();
        $data['license_id'] = $request->header('X-License-Id');
        $data['client_id'] = $this->licenseService->get($data['license_id'])->client_id;
        $this->lockerSystemLogService->add($data);
    }
}
