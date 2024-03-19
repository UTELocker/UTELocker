<?php

namespace App\Http\Controllers\Api\Bookings;

use App\Classes\Reply;
use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Bookings\ChangePassRequest;
use App\Http\Requests\Api\Bookings\PutExtendTimeRequest;
use App\Http\Requests\Api\Bookings\StoreBookingRequest;
use App\Models\License;
use App\Models\Transaction;
use App\Models\LockerSlot;
use App\Enums\LockerSlotType;
use App\Services\Admin\Bookings\BookingService;
use App\Services\Wallets\TransactionService;
use App\Services\Wallets\WalletService;
use Illuminate\Http\Request;
use App\Enums\BookingStatus;
use App\Traits\HandleNotification;

class BookingController extends Controller
{
    use HandleNotification;

    public ?BookingService $bookingService;
    public ?TransactionService $transactionService;

    public WalletService $walletService;

    public function __construct(
        BookingService $bookingService,
        TransactionService $transactionService,
        WalletService $walletService
    ) {
        parent::__construct();
        $this->bookingService = $bookingService;
        $this->transactionService = $transactionService;
        $this->walletService = $walletService;
    }

    public function getBookingActivities(Request $request)
    {
        $licenseId = $request->get('licenseId') ?? null;
        $bookings = $this->bookingService->getBookingActivities(user(), $licenseId);
        return Reply::successWithData('Get bookings successfully',
            [
                'data' => $this->bookingService->formatOutputApi($bookings)
            ]
        );
    }

    public function show($id)
    {
        $bookings = $this->bookingService->get($id);
        return Reply::successWithData('Get bookings successfully',
            [
                'data' => $bookings
            ]
        );
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->all();
        $bookings = $this->bookingService->addBooking($data);
        if ($bookings['status'] == 'success') {
            return Reply::successWithData('Create bookings successfully',
                [
                    'data' => $bookings['data']
                ]);
        } else {
            return Reply::error($bookings['message']);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['user_id'] = \auth()->user()->id;
        $data['client_id'] = \auth()->user()->client_id;
//        $bookings = $this->bookingService->update($id, $data);
//        return Reply::successWithData('Update bookings successfully',
//            [
//                'data' => $bookings
//            ]);
    }

    public function destroy($id)
    {
        $booking = $this->bookingService->get($id);
        $this->bookingService->delete($booking);
        return Reply::success('Delete bookings successfully');
    }

    public function changePassword(ChangePassRequest $request)
    {
        $idBooking = $request->get('id');
        $oldPassword = $request->get('oldPassword');
        $bookings = $this->bookingService->changePassword($idBooking, $oldPassword);
        if ($bookings) {
            return Reply::successWithData('Change password successfully',
                [
                    'data' => $bookings
                ]);
        } else {
            return Reply::error('Change password failed');
        }
    }

    public function getHistoriesBooking()
    {
        $bookings = $this->bookingService->getHistoriesBooking(user());
        return Reply::successWithData('Get histories booking successfully',
            [
                'data' => [
                    'bookings' => $bookings,
                ]
            ]
        );
    }

    public function extendTime($id, PutExtendTimeRequest $request)
    {
        $data = $request->all();
        $bookings = $this->bookingService->extendTime($id, $data);
        return Reply::successWithData('Extend time successfully',
            [
                'data' => $bookings
            ]
        );
    }

    public function getBookingsBySlotId ($slotId)
    {
        $bookings = $this->bookingService->getBookingsBySlotId($slotId);
        return Reply::successWithData('Get bookings by slot id successfully',
            [
                'data' => $bookings
            ]
        );
    }

    public function openSlotLocker ($bookingId) {
        $booking = $this->bookingService->get($bookingId);

        if ($booking->status == BookingStatus::APPROVED || $booking->status == BookingStatus::APPROVED) {
            $lockerId = LockerSlot::where('id', $booking->locker_slot_id)->first()->locker_id;
            $slotsInLocker = LockerSlot::where('locker_id', $lockerId)
                ->select('id', 'row', 'column', 'type')
                ->orderBy('row')
                ->orderBy('column')
                ->get();

            $count = 1;
            foreach ($slotsInLocker as $slot) {
                if ($slot->id == $booking->locker_slot_id) {
                    break;
                }
                if ($slot->type == LockerSlotType::SLOT) {
                    $count++;
                }
            }

            return Reply::success('Open slot locker successfully');
        }

        return Reply::error('Open slot locker failed');
    }
}
