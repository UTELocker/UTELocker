@switch($row->status)
    @case(\App\Enums\BookingStatus::APPROVED)
        <i class="fa fa-circle mr-1 f-10" style="color: #003dff;"></i> {{ __('app.enums.bookingStatus.approved')}}
        @break
    @case(\App\Enums\BookingStatus::PENDING)
        <i class="fa fa-circle mr-1 f-10" style="color: #f3fb00;"></i> {{ __('app.enums.bookingStatus.pending')}}
        @break
    @case(\App\Enums\BookingStatus::COMPLETED)
        <i class="fa fa-circle mr-1 f-10" style="color: #38ff00;"></i> {{ __('app.enums.bookingStatus.completed')}}
        @break
    @case(\App\Enums\BookingStatus::CANCELLED)
        <i class="fa fa-circle mr-1 f-10" style="color: #ffbc00;"></i> {{ __('app.enums.bookingStatus.cancelled')}}
        @break
    @case(\App\Enums\BookingStatus::EXPIRED)
        <i class="fa fa-circle mr-1 f-10" style="color: #ff0000;"></i> {{ __('app.enums.bookingStatus.expired')}}
        @break

    @case(\App\Enums\BookingStatus::REJECTED)
        <i class="fa fa-circle mr-1 f-10" style="color: #00ffd9;"></i> {{ __('app.enums.bookingStatus.rejected')}}
        @break
    @default
        <i class="fa fa-circle mr-1 f-10" style="color: #9900ff;"></i> {{ __('app.enums.bookingStatus.locked')}}
        @break
@endswitch
