<?php

namespace App\Services\Admin\Bookings;

use App\Classes\Common;
use App\Enums\BookingStatus;
use App\Enums\HistoryLimitTime;
use App\Models\Booking;
use App\Models\Location;
use App\Models\Locker;
use App\Models\LockerSlot;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingsService extends BaseService
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    public function initDefaultData(): static
    {
        return $this;
    }

    public function addListBooking(array $inputs)
    {
        $listSlotsId = $inputs['list_slots_id'];
        $listBookings = [];
        foreach ($listSlotsId as $slotId) {
            DB::transaction(function () use ($slotId, $inputs, &$listBookings) {
                $inputs['locker_slot_id'] = $slotId;
                $listBookings[] = $this->add($inputs);
            });
        }
        return $listBookings;
    }

    public function add(array $inputs)
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        DB::transaction(function () {
            $this->model->save();
        });

        return $this->model;
    }

    protected function formatInputData(&$inputs)
    {
        $inputs['pin_code'] = $inputs['pin_code'] ?? $this->randomPinCode();
        $inputs['client_id'] = $inputs['client_id'] ?? auth()->user()->client_id;
        $inputs['owner_id'] = $inputs['owner_id'] ?? auth()->user()->id;
        $inputs['status'] = $inputs['status'] ?? BookingStatus::APPROVED;
    }

    private function randomPinCode()
    {
        return rand(100000, 999999);
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'client_id', $inputs);
        Common::assignField($this->model, 'locker_slot_id', $inputs);
        Common::assignField($this->model, 'owner_id', $inputs);
        Common::assignField($this->model, 'status', $inputs);
        Common::assignField($this->model, 'pin_code', $inputs);
        Common::assignField($this->model, 'start_date', $inputs);
        Common::assignField($this->model, 'end_date', $inputs);
    }

    public function get($id) {
        return $this->model->findOrfail($id);
    }

    public function getAllOfUser($userId, $clientId) {
        return $this->model
            ->where('bookings.owner_id', $userId)
            ->where('bookings.client_id', $clientId)
            ->where(function ($query) {
                $query->where('bookings.status', BookingStatus::APPROVED)
                    ->orWhere('bookings.status', BookingStatus::PENDING);
            })
            ->leftJoin('locker_slots', 'bookings.locker_slot_id', '=', 'locker_slots.id')
            ->leftJoin('lockers', 'locker_slots.locker_id', '=', 'lockers.id')
            ->leftJoin('locations', 'lockers.location_id', '=', 'locations.id')
            ->select(
                'bookings.pin_code', 'bookings.status', 'bookings.start_date',
                'bookings.end_date', 'bookings.created_at', 'bookings.id',
                'locker_slots.row', 'locker_slots.column', 'locker_slots.locker_id',
                'locker_slots.config', 'lockers.image',
                'locations.description as address', 'locations.latitude', 'locations.longitude'
            )
            ->orderBy('locker_slots.locker_id', 'asc')
            ->orderBy('locker_slots.row', 'asc')
            ->orderBy('locker_slots.column', 'asc')
            ->get();
    }
    public function fomartOutputApi($bookings) {
        $locker = null;
        $listNameSlotInLocker = [];

        foreach ($bookings as $booking) {
            if (empty($locker) || $locker !== $booking->locker_id) {
                $locker = $booking->locker_id;
                $listSlotInLocker = LockerSlot::where('locker_id', $locker)
                    ->orderBy('row', 'asc')
                    ->orderBy('column', 'asc')
                    ->get();
                $listNameSlotInLocker = LockerSlot::getListNameSlots($listSlotInLocker);
            }

            $startDateTime = explode(' ', $booking->start_date);
            $endDateTime = explode(' ', $booking->end_date);
            $totalMinutes = strtotime($booking->end_date) - strtotime($booking->start_date);
            $totalPrice = ($booking->config->price_per_minute ?? 10 )* $totalMinutes;

            if ($booking->start_date < Carbon::now() && $booking->end_date > Carbon::now()) {
                $timeOut = strtotime($booking->end_date) - strtotime(Carbon::now());
            } elseif ($booking->start_date > Carbon::now()) {
                $timeOut = 'Not yet';
            } else {
                $timeOut = 'Expired';
            }

            $result[] = [
                'id' => $booking->id,
                'code' => $listNameSlotInLocker[$booking->row . '-' . $booking->column],
                'pin_code' => $booking->pin_code,
                'status' => $booking->status,
                'address' => $booking->address,
                'timeOut' => $timeOut,
                'dateBooked' => [
                    'start' => [
                        'date' => $startDateTime[0],
                        'time' => $startDateTime[1],
                    ],
                    'end' => [
                        'date' => $endDateTime[0],
                        'time' => $endDateTime[1],
                    ],
                ],
                'dateRequest' => Carbon::parse($booking->created_at)->format('Y-m-d H:i:s'),
                'totalPrice' => $totalPrice,
                'location' => [
                    'latitude' => $booking->latitude,
                    'longitude' => $booking->longitude,
                ],
            ];
        }

        return $result;
    }

    public function changePassword($id, $oldPassword)
    {
        $booking = $this->model->findOrfail($id);
        if ($booking->pin_code !== $oldPassword) {
            return false;
        }
        $booking->pin_code = $this->randomPinCode();
        $booking->save();
        return $booking;
    }

    public function delete($id)
    {
        $booking = $this->model->findOrfail($id);
        $booking->status = BookingStatus::CANCELLED;
        $booking->save();
        return $booking;
    }

    public function getHistoriesBooking($userId, $clientId) {
        return $this->model
            ->where('bookings.owner_id', $userId)
            ->where('bookings.client_id', $clientId)
            ->where('bookings.start_date', '>=', Carbon::now()->subMonths(HistoryLimitTime::LIMIT_MONTH_BOOKING))
            ->leftJoin('locker_slots', 'bookings.locker_slot_id', '=', 'locker_slots.id')
            ->leftJoin('lockers', 'locker_slots.locker_id', '=', 'lockers.id')
            ->leftJoin('locations', 'lockers.location_id', '=', 'locations.id')
            ->select(
                'bookings.pin_code', 'bookings.status', 'bookings.start_date',
                'bookings.end_date', 'bookings.created_at', 'bookings.id',
                'locker_slots.row', 'locker_slots.column', 'locker_slots.locker_id',
                'locker_slots.config', 'lockers.image',
                'locations.description as address', 'locations.latitude', 'locations.longitude'
            )
            ->orderBy('locker_slots.locker_id', 'asc')
            ->orderBy('locker_slots.row', 'asc')
            ->orderBy('locker_slots.column', 'asc')
            ->get();
    }
}
