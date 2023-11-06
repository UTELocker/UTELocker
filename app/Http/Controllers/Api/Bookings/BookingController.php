<?php

namespace App\Http\Controllers\Api\Bookings;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\bookings\ChangePassRequest;
use App\Http\Requests\Api\Bookings\PutExtendTimeRequest;
use App\Http\Requests\Api\Bookings\StoreBookingRequest;
use App\Services\Admin\Bookings\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public ?BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        parent::__construct();
        $this->bookingService = $bookingService;
    }

    public function getBookingActivities()
    {
        $bookings = $this->bookingService->getBookingActivities(user());
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
        $bookings = $this->bookingService->addListBooking($data);
        return Reply::successWithData('Create bookings successfully',
            [
                'data' => $bookings
            ]
        );
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
        $this->bookingService->delete($id);
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
                'data' => $bookings
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

    public function getBookingsBySlotId ($slotId) {
        $bookings = $this->bookingService->getBookingsBySlotId($slotId);
        return Reply::successWithData('Get bookings by slot id successfully',
            [
                'data' => $bookings
            ]
        );
    }

}
