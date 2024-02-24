<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HandleNotification;
use App\Enums\NotificationType;
use App\Services\Admin\Lockers\LockerSlotService;
use App\Services\Admin\Bookings\BookingService;
use App\Services\LockeSystem\LockerSystemLogService;
use App\Services\Admin\Licenses\LicenseService;
use App\Services\Admin\Clients\ClientService;
use App\Classes\Reply;

class LockerSystemController extends Controller
{
    use HandleNotification;

    protected ?LockerSlotService $lockerSlotService;
    protected ?BookingService $bookingService;
    protected ?LockerSystemLogService $lockerSystemLogService;
    protected ?LicenseService $licenseService;
    protected ?ClientService $clientService;

    use HandleNotification;

    public function __construct(
        LockerSlotService $lockerSlotService,
        BookingService $bookingService,
        LockerSystemLogService $lockerSystemLogService,
        LicenseService $licenseService,
        ClientService $clientService
    ) {
        $this->lockerSlotService = $lockerSlotService;
        $this->bookingService = $bookingService;
        $this->lockerSystemLogService = $lockerSystemLogService;
        $this->licenseService = $licenseService;
        $this->clientService = $clientService;
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
        return Reply::success('Log active successfully');
    }

    public function settingsLockerSystem (Request $request) {
        $settings = $this->clientService->getClientByLicenseId($request->header('X-License-Id'));
        $globalSettings = globalSettings();
        return Reply::successWithData('Settings found', [
            'data' => [
                'settings' => $settings,
                'globalSettings' => [
                    'pusher_app_id' => $globalSettings->pusher_app_id,
                    'pusher_app_key' => $globalSettings->pusher_app_key,
                    'pusher_app_secret' => $globalSettings->pusher_app_secret,
                    'pusher_app_cluster' => $globalSettings->pusher_app_cluster,
                ],
            ],
        ]);
    }

    public function resetPass(Request $request) {
        $this->lockerSlotService->resetPassSlot(
            $request->password,
            $request->header('X-License-Id')
        );

        return Reply::success('Reset pass successfully');
    }

    function pusher()
    {
        $this->sendNotification(
            NotificationType::LOCKER_SYSTEM,
            'Ngăn tủ tại ' . 'address' . ' đã được mở',
            1,
            1,
        );
    }
}
