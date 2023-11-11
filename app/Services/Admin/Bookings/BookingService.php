<?php

namespace App\Services\Admin\Bookings;

use App\Classes\Common;
use App\Classes\CommonConstant;
use App\Classes\Reply;
use App\Enums\BookingActivitiesStatus;
use App\Enums\BookingStatus;
use App\Enums\HistoryLimitTime;
use App\Models\Booking;
use App\Models\Location;
use App\Models\Locker;
use App\Models\LockerSlot;
use App\Models\User;
use App\Services\BaseService;
use App\Services\Wallets\TransactionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Classes\Files;

class BookingService extends BaseService
{
    private TransactionService $transactionService;
    public function __construct(Booking $model, TransactionService $transactionService)
    {
        parent::__construct($model);
        $this->transactionService = $transactionService;
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
        $inputs['status'] = $inputs['status'] ?? BookingStatus::PENDING;
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

    public function getBookingActivities(User $user) {
        return $this->model
            ->where('bookings.owner_id', $user->id)
            ->where('bookings.client_id', $user->client_id)
            ->where(function ($query) {
                $query->where('bookings.status', BookingStatus::APPROVED)
                    ->orWhere('bookings.status', BookingStatus::PENDING);
            })
            ->leftJoin('locker_slots', 'bookings.locker_slot_id', '=', 'locker_slots.id')
            ->leftJoin('lockers', 'locker_slots.locker_id', '=', 'lockers.id')
            ->leftJoin('locations', 'lockers.location_id', '=', 'locations.id')
            ->select(
                'lockers.id as locker_id', 'locker_slots.id as locker_slot_id',
                'bookings.pin_code', 'bookings.start_date',
                'bookings.end_date', 'bookings.created_at', 'bookings.id',
                'lockers.code', 'lockers.image',
                'locker_slots.row', 'locker_slots.column', 'locker_slots.locker_id',
                'locker_slots.config',
                'locations.description as address', 'locations.latitude', 'locations.longitude'
            )
            ->orderBy('locker_slots.locker_id', 'asc')
            ->orderBy('locker_slots.row', 'asc')
            ->orderBy('locker_slots.column', 'asc')
            ->get();
    }
    public function formatOutputApi($bookings) {
        $locker = null;
        $listNameSlotInLocker = [];

        foreach ($bookings as $booking) {
            if (empty($locker) || $locker !== $booking->locker_id) {
                $locker = $booking->locker_id;
                $listSlotInLocker = LockerSlot::where('locker_id', $locker)
                    ->orderBy('row', 'asc')
                    ->orderBy('column', 'asc')
                    ->get();
                $listNameSlotInLocker = Common::getListNameSlots($listSlotInLocker);
            }

            $startDateTime = explode(' ', $booking->start_date);
            $endDateTime = explode(' ', $booking->end_date);
            $totalMinutes = strtotime($booking->end_date) - strtotime($booking->start_date);
            $totalPrice = ($booking->config->price_per_minute ?? 10 )* $totalMinutes;

            $now = Carbon::now();
            $endTime = new Carbon($booking->end_date);
            $startTime = new Carbon($booking->start_date);
            if ($startTime < $now && $endTime > $now) {
                $status = BookingActivitiesStatus::ACTIVE;
                $timeRemainMinutes = $now->diffInMinutes($endTime);
                $daysRemain = round($timeRemainMinutes / 60 / 24);
                $hoursRemain = $timeRemainMinutes / 60 % 24;
                $timeRemain = [
                    'days' => $daysRemain,
                    'hours' => $hoursRemain,
                    'minutes' => $timeRemainMinutes - $daysRemain * 24 * 60 - $hoursRemain * 60,
                ];
            } elseif ($startTime > $now) {
                $status = BookingActivitiesStatus::NOT_YET;
            } else {
                $status = BookingActivitiesStatus::EXPIRED;
            }

            $result[] = [
                'id' => $booking->id,
                'code' => $listNameSlotInLocker[$booking->row . '-' . $booking->column],
                'pin_code' => $booking->pin_code,
                'status' => $status,
                'address' => $booking->address,
                'timeOut' => $timeRemain ?? ['days' => 0, 'hours' => 0, 'minutes' => 0,],
                'lockerCode' => $booking->code,
                'lockerSlotConfig' => $booking->config,
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
                'lockerImage' => $booking->image ? Files::getImageUrl(
                    $booking->image, 'client-locker',
                    Files::CLIENT_UPLOAD_FOLDER
                ) :
                    asset('images/default/lockerDefault.png'),
                'lockerId' => $booking->locker_id,
                'lockerSlotId' => $booking->locker_slot_id,
            ];
        }

        return $result ?? [];
    }

    public function changePassword($id, $oldPassword)
    {
        $booking = $this->get($id);
        if ($booking->pin_code !== $oldPassword) {
            return false;
        }
        $booking->pin_code = $this->randomPinCode();
        $booking->save();
        return $booking;
    }

    public function delete($id)
    {
        $booking = $this->get($id);
        $booking->status = BookingStatus::CANCELLED;
        $booking->save();
        return $booking;
    }

    public function extendTime($id, array $data)
    {
        $booking = $this->get($id);
        $extendDate = Carbon::parse($booking->end_date)->addMinutes($data['extend_time']);
        $booking->end_date = $extendDate;
        $booking->save();
        return $booking;
    }

    public function getActiveBookingOfSlot(LockerSlot $lockerSlot)
    {
        return $lockerSlot->bookings()->where('status', BookingStatus::APPROVED)
            ->select('bookings.id', 'bookings.status', 'bookings.start_date', 'bookings.end_date', 'owner_id')
            ->where('bookings.end_date', '>=', now())
            ->where('bookings.start_date', '<=', now())
            ->first();
    }

    public function getBookingsBySlotId($slotId)
    {
        return $this->model->where('locker_slot_id', $slotId)
            ->whereIn('status', [BookingStatus::APPROVED, BookingStatus::PENDING])
            ->where('start_date', '>=', now()->subDays(7))
            ->select(
                'id', 'start_date', 'end_date', 'status'
            )
            ->get();
    }

    public function addBooking($data)
    {
        // DB begin
        try {
            return DB::transaction(function () use ($data) {
                $bookings = $this->addListBooking($data);
                $wallet = user()->wallet;
                $amount = LockerSlot::caculatePriceBooking($data['list_slots_id'], $data['start_date'], $data['end_date']);
                $transaction =$this->transactionService->handlePayment(
                    $wallet,
                    $amount,
                    'Thanh toán đặt tủ',
                );
                if (!$transaction) {
                    throw new \Exception('Thanh toán thất bại');
                }

                $listId = [];
                foreach ($bookings as $booking) {
                    $listId[] = $booking->id;
                }
                $this->model->whereIn('id', $listId)->update(['transaction_id' => $transaction->id]);

                return [
                    'status' => 'success',
                    'data' => $bookings,
                ];
            });
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage(),
                'status' => 'error',
            ];
        }
    }

    public function numBooking(Locker $locker)
    {
        $data = [];
        $bookings = $this->model
            ->leftJoin('locker_slots', 'bookings.locker_slot_id', '=', 'locker_slots.id')
            ->where('locker_slots.locker_id', $locker->id)
            ->whereIn('bookings.status', [BookingStatus::APPROVED, BookingStatus::PENDING, BookingStatus::COMPLETED])
            ->select(
                DB::raw('DATE_FORMAT(bookings.start_date, "%m") as month'),
            )
            ->get();
        $bookings = $bookings->where('month', '<=', Carbon::now()->format('m'));
        $moths = [];
        for ($i = 5; $i >= 0 ; $i--) {
            $moths[] = Carbon::now()->subMonths($i)->format('m');
        }
        $data['labels'] = $moths;

        for ($i = 0; $i < 6; $i++) {
            $data['values'][] = $bookings->where('month', $moths[$i])->count();
        }
        $data['colors'] = ['#ff6384'];
        $data['name'] = __('modules.lockers.sumEarnings');
        return $data;
    }

    public function sumEarn(\Illuminate\Database\Eloquent\Model $locker)
    {
        $data = [];
        $bookings = $this->model
            ->leftJoin('locker_slots', 'bookings.locker_slot_id', '=', 'locker_slots.id')
            ->leftJoin('transactions', 'bookings.transaction_id', '=', 'transactions.id')
            ->where('locker_slots.locker_id', $locker->id)
            ->whereIn('bookings.status', [BookingStatus::APPROVED, BookingStatus::PENDING, BookingStatus::COMPLETED])
            ->select([
                DB::raw('DATE_FORMAT(bookings.start_date, "%m") as month'),
                'transactions.amount'
            ])
            ->get();
        $bookings = $bookings->where('month', '<=', Carbon::now()->format('m'));
        $moths = [];
        for ($i = 0; $i < 6; $i++) {
            $moths[] = Carbon::now()->subMonths($i)->format('m');
        }
        $data['labels'] = $moths;
        $data['values'] = $bookings->groupBy('month')->map(function ($item) {
            return $item->sum('amount');
        })->values()->toArray();
        $data['colors'] = ['#36a2eb'];
        $data['name'] = __('modules.lockers.sumEarnings');
        return $data;
    }

    public function getHistoryLine(mixed $booking)
    {
        $created = $this->pointHistory(__('modules.bookings.create'), 'created', $booking->created_at);
        $startDate = $this->pointHistory(__('modules.bookings.startDate'), 'start', $booking->start_date);
        $endDate = $this->pointHistory(__('modules.bookings.endDate'), 'end', $booking->end_date);
        $cancelled = false;
        if ($booking->status == BookingStatus::CANCELLED) {
            $cancelled = $this->pointHistory(__('modules.bookings.cancelled'), 'cancelled', $booking->updated_at);
        }

        if ($cancelled) {
            if ($booking->updated_at->gt($booking->end_date)) {
                return [$created, $startDate, $endDate, $cancelled];
            } else {
                if ($booking->updated_at->gt($booking->start_date)) {
                    return [$created, $startDate, $cancelled, $endDate];
                } else {
                    return [$created, $cancelled, $startDate, $endDate];
                }
            }
        }
        return [$created, $startDate, $endDate];
    }

    private function pointHistory($subject, $status, $date)
    {
        return [
            'subject' => $subject,
            'status' => $status,
            'date' => Common::formatDateBaseOnSetting($date, user()->isSuperUser())
        ];
    }

    public function getSumBookingInTransaction($transactionId)
    {
        return $this->model->where('transaction_id', $transactionId)->get()->count();
    }

    public function getHistoriesBookingClient($bookingId = null)
    {
        return $this->model
            ->fullDetails()
            ->when($bookingId == null && !user()->isSuperUser(), function ($query) {
                return $query->where('clients.id', user()->client_id);
            })
            ->when($bookingId != null, function ($query) use ($bookingId) {
                return $query->where('bookings.id', $bookingId);
            })
            ->get();
    }

    public function getHistoriesBooking(User $user, $bookingId = null)
    {
        return $this->model
            ->fullDetails()
            ->where('bookings.start_date', '>=', Carbon::now()->subMonths(CommonConstant::LIMIT_MONTH_BOOKING))
            ->when($bookingId == null, function ($query) use ($user) {
                return $query->where('bookings.owner_id', $user->id);
            })
            ->when($bookingId != null, function ($query) use ($bookingId) {
                return $query->where('bookings.id', $bookingId);
            })
            ->get();
    }
}
