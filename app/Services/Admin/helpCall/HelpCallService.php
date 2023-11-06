<?php

namespace App\Services\Admin\helpCall;

use App\Classes\Common;
use App\Models\HelpCall;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Classes\Files;
use App\Enums\HelpCallType;
use App\Enums\HelpCallStatus;

class HelpCallService extends BaseService {


    public function __construct()
    {
        parent::__construct(new HelpCall());
    }

    public function add(array $inputs)
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $this->model->save();
        return $this->model;
    }

    public function update(HelpCall $helpCall, array $inputs)
    {
        $this->setModel($helpCall);
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $this->model->save();
        return $this->model;
    }

    protected function formatInputData(&$inputs)
    {
        if (isset($inputs['attachment'])) {
            $files = [];
            foreach ($inputs['attachment'] as $file) {
                $files[] = $file['originFileObj'];
            }

            $inputs['attachment'] = implode(',', Files::uploadMultipleFiles(
                $files,
                'help-call',
                width: 300,
                options: ['isUser' => true]
            ));
        } else {
            $inputs['attachment'] = $this->model->attachment;
        }

        $inputs['client_id'] = user()->client_id;
        $inputs['log_created_at'] = $inputs['log_created_at'] ?? now();
        $inputs['owner_id'] = $inputs['owner_id'] ?? user()->id;
        $inputs['status'] = $inputs['status'] ?? HelpCallStatus::PENDING;

        if(isset($inputs['type'])) {
            switch ($inputs['type']) {
                case HelpCallType::LOCKER:
                    $inputs['src_id'] = $inputs['lockerId'];
                    $inputs['help_call_src'] = HelpCallType::getTable()[HelpCallType::LOCKER];
                    break;
                case HelpCallType::LOCKER_SLOT:
                    $inputs['src_id'] = $inputs['lockerSlotId'];
                    $inputs['help_call_src'] = HelpCallType::getTable()[HelpCallType::LOCKER_SLOT];
                    break;
                case HelpCallType::BOOKING:
                    $inputs['src_id'] = $inputs['bookingId'];
                    $inputs['help_call_src'] = HelpCallType::getTable()[HelpCallType::BOOKING];
                    break;
                default:
                    break;
            }
        } else {
            $inputs['type'] = $this->model->type;
            $inputs['help_call_src'] = $this->model->help_call_src;
            $inputs['src_id'] = $this->model->src_id;
        }

        $inputs['help_call_std_problems_id'] = $inputs['helpCallstdProblemId'] ??
            $this->model->help_call_std_problems_id;

        $inputs['supporter_id'] = $inputs['supporterId'] ?? $this->model->supporter_id;
        $inputs['content'] = $inputs['content'] ?? $this->model->content;
        $inputs['title'] = $inputs['title'] ?? $this->model->title;
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'status', $inputs);
        Common::assignField($this->model, 'type', $inputs);
        Common::assignField($this->model, 'src_id', $inputs);
        Common::assignField($this->model, 'priority', $inputs);
        Common::assignField($this->model, 'title', $inputs);
        Common::assignField($this->model, 'content', $inputs);
        Common::assignField($this->model, 'attachment', $inputs);
        Common::assignField($this->model, 'owner_id', $inputs);
        Common::assignField($this->model, 'supporter_id', $inputs);
        Common::assignField($this->model, 'client_id', $inputs);
        Common::assignField($this->model, 'location_id', $inputs);
        Common::assignField($this->model, 'help_call_std_problems_id', $inputs);
        Common::assignField($this->model, 'log_created_at', $inputs);
        Common::assignField($this->model, 'help_call_src', $inputs);
    }

    public function getHelpCallsUser()
    {
        return $this->model
            ->leftJoin('users as supporter', 'supporter.id', '=', 'help_calls.supporter_id')
            ->leftJoin('help_call_std_problems as std_problem',
                'std_problem.id', '=', 'help_calls.help_call_std_problems_id')
            ->where('help_calls.client_id', user()->client_id)
            ->select (
                'help_calls.id',
                'help_calls.type',
                'help_calls.status',
                'help_calls.title',
                'help_calls.log_created_at',
                'supporter.name as supporter_name',
                'std_problem.description as std_problem_description'
            )
            ->get();
    }

    public function getHelpCallAdmin()
    {
        return $this->model
            ->leftJoin('users as supporter', 'supporter.id', '=', 'help_calls.supporter_id')
            ->leftJoin('users as owner', 'owner.id', '=', 'help_calls.owner_id')
            ->leftJoin('help_call_std_problems as std_problem',
                'std_problem.id', '=', 'help_calls.help_call_std_problems_id')
            ->where('help_calls.client_id', user()->client_id)
            ->select (
                'help_calls.id',
                'help_calls.type',
                'help_calls.status',
                'help_calls.title',
                'help_calls.log_created_at',
                'supporter.name as supporter_name',
                'owner.name as owner_name',
                'std_problem.description as std_problem_description'
            )
            ->get();
    }

    public function getWithComment($id)
    {
       $query = $this->model
            ->leftJoin('users as supporter', 'supporter.id', '=', 'help_calls.supporter_id')
            ->leftJoin('users as owner', 'owner.id', '=', 'help_calls.owner_id')
            ->leftJoin('help_call_std_problems as std_problem',
                'std_problem.id', '=', 'help_calls.help_call_std_problems_id')
            ->where('help_calls.id', $id)
            ->select (
                'help_calls.*',
                'supporter.name as supporter_name',
                'owner.name as owner_name',
                'std_problem.description as std_problem_description'
            )
            ->with(['comments' => function ($q) {
                $q->leftJoin('users', 'users.id', '=', 'help_call_comments.owner_id')
                    ->select(
                        'help_call_comments.help_call_id',
                        'help_call_comments.content',
                        'help_call_comments.created_at',
                        'users.name as user_name',
                        'users.avatar as user_avatar'
                    )
                    ->orderBy('created_at', 'desc');
            }])
            ->first();
        if ($query->help_call_src) {
            switch ($query->type) {
                case HelpCallType::LOCKER:
                    $src = DB::table(HelpCallType::getTable()[$query->type])
                        ->leftJoin('locations', 'locations.id', '=', 'lockers.location_id')
                        ->select(
                            'lockers.code as locker_code',
                            'locations.description as address'
                        )
                        ->where('lockers.id', $query->src_id)->first();
                    $query->src = $src;
                    break;
                case HelpCallType::LOCKER_SLOT:
                    $slots = DB::table(HelpCallType::getTable()[$query->type])
                        ->leftJoin('lockers', 'lockers.id', '=', 'locker_slots.locker_id')
                        ->leftJoin('locations', 'locations.id', '=', 'lockers.location_id')
                        ->select(
                            'lockers.code as locker_code',
                            'locations.description as address',
                            'locker_slots.row',
                            'locker_slots.column',
                            'locker_slots.config',
                            'locker_slots.type',
                            'locker_slots.id as id'
                        )
                        ->where( function ($q) use ($query) {
                            $lockerId = DB::table('locker_slots')
                                ->where('id', $query->src_id)
                                ->select(
                                    'locker_id',
                                )
                                ->first();
                            $q->where('lockers.id', $lockerId->locker_id);
                        })
                        ->orderBy('row', 'asc')
                        ->orderBy('column', 'asc')
                        ->get();

                    $slotCode = Common::getListNameSlots($slots, $query->src_id);
                    $src = $slots->where('id', $query->src_id)->first();
                    $src->slotCode = $slotCode;
                    $query->src = $src;
                    break;
                case HelpCallType::BOOKING:
                    $slots = DB::table(HelpCallType::getTable()[$query->type])
                        ->rightJoin('locker_slots', 'locker_slots.id', '=', 'bookings.locker_slot_id')
                        ->leftJoin('lockers', 'lockers.id', '=', 'locker_slots.locker_id')
                        ->leftJoin('locations', 'locations.id', '=', 'lockers.location_id')
                        ->where( function ($q) use ($query) {
                            $lockerId = DB::table('bookings')
                                ->leftJoin('locker_slots', 'locker_slots.id', '=', 'bookings.locker_slot_id')
                                ->leftJoin('lockers', 'lockers.id', '=', 'locker_slots.locker_id')
                                ->where('bookings.id', $query->src_id)
                                ->select(
                                    'lockers.id',
                                )
                                ->first();
                            $q->where('lockers.id', $lockerId->id);
                        })
                        ->select(
                            'locker_slots.id as id',
                            'lockers.code as locker_code',
                            'locations.description as address',
                            'bookings.start_date',
                            'bookings.end_date',
                            'bookings.status as booking_status',
                            'bookings.id as booking_id',
                            'locker_slots.row',
                            'locker_slots.column',
                            'locker_slots.config',
                            'locker_slots.type'
                        )
                        ->get();
                    $src = $slots->where('booking_id', $query->src_id)->first();
                    $slotCode = Common::getListNameSlots($slots, $src->id);
                    $src->slotCode = $slotCode;
                    $query->src = $src;
                    break;
                default:
                    $query->src = null;
                    break;
            }
        }
        return $query;
    }
}
