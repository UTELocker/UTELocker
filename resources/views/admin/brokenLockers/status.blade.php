@switch($row->status)
    @case(\App\Enums\LockerStatus::AVAILABLE)
        <i class="fa fa-circle mr-1 f-10" style="color: #5dff00;"></i>
        @break
    @case(\App\Enums\LockerStatus::IN_USE)
        <i class="fa fa-circle mr-1 f-10" style="color: #5dff00;"></i>
        @break
    @case(\App\Enums\LockerStatus::UNDER_MAINTENANCE)
        <i class="fa fa-circle mr-1 f-10" style="color: #ff0000;"></i>
        @break
    @case(\App\Enums\LockerStatus::BROKEN)
        <i class="fa fa-circle mr-1 f-10" style="color: #ff0000;"></i>
        @break
    @default
        <i class="fa fa-circle mr-1 f-10" style="color: #f3fb00;"></i>
        @break
@endswitch
{{  \App\Enums\LockerStatus::getLabel($row->status) }}
