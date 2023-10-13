<?php

namespace App\Http\Controllers\Api\Bookings;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
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
}
