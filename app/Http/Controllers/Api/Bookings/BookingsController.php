<?php

namespace App\Http\Controllers\Api\Bookings;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Bookings\StoreBookingRequest;
use App\Services\Admin\Bookings\BookingsService;
use App\View\Components\Auth;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public ?BookingsService $bookingsService;

    public function __construct(BookingsService $bookingsService)
    {
        $this->bookingsService = $bookingsService;
    }

    public function getOfUser()
    {
        $userId = \auth()->user()->id;
        $clientId = \auth()->user()->client_id;
        $bookings = $this->bookingsService->getAllOfUser($userId, $clientId);
        return Reply::successWithData('Get bookings successfully',
            [
                'data' => $this->bookingsService->fomartOutputApi($bookings)
            ]);
    }

    public function show($id)
    {
        $bookings = $this->bookingsService->get($id);
        return Reply::successWithData('Get bookings successfully',
            [
                'data' => $bookings
            ]);
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->all();
        $bookings = $this->bookingsService->addListBooking($data);
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
//        $bookings = $this->bookingsService->update($id, $data);
//        return Reply::successWithData('Update bookings successfully',
//            [
//                'data' => $bookings
//            ]);
    }

    public function destroy($id)
    {
        $bookings = $this->bookingsService->delete($id);
//        return Reply::successWithData('Delete bookings successfully',
//            [
//                'data' => $bookings
//            ]);
    }
}
