<?php

namespace App\Http\Controllers\Api\Bookings;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\bookings\ChangePassRequest;
use App\Http\Requests\Api\Bookings\StoreBookingRequest;
use App\Services\Admin\Bookings\BookingService;
use App\View\Components\Auth;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public ?BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function getOfUser()
    {
        $userId = user()->id;
        $clientId = user()->client_id;
        $bookings = $this->bookingService->getAllOfUser($userId, $clientId);
        return Reply::successWithData('Get bookings successfully',
            [
                'data' => $this->bookingService->fomartOutputApi($bookings)
            ]);
    }

    public function show($id)
    {
        $bookings = $this->bookingService->get($id);
        return Reply::successWithData('Get bookings successfully',
            [
                'data' => $bookings
            ]);
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->all();
        $bookings = $this->bookingService->addListBooking($data);
        return Reply::successWithData('Create bookings successfully',
            [
                'data' => $bookings
            ]);
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
        $userId = user()->id;
        $clientId = user()->client_id;
        $bookings = $this->bookingService->getHistoriesBooking($userId, $clientId);
        return Reply::successWithData('Get histories booking successfully',
            [
                'data' => $bookings
            ]);
    }
}