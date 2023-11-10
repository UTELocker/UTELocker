<div class="row mt-4">
    <div class="col-xl-7 col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4">
        <x-cards.data :title="__('modules.lockers.lockerDetails')">
            <x-cards.data-row :label="__('modules.lockers.code')" :value="$locker->code ?? '--'" />

            <x-cards.data-row :label="__('modules.lockers.description')" :value="$locker->description ?? '--'" />
            <div class="col-12 px-0 pb-3 d-block d-lg-flex d-md-flex">
                <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                    @lang('modules.lockers.lockerPicture')</p>
                <p class="mb-0 text-dark-grey f-14 w-70">
                    @if ($locker)
                        <img data-toggle="tooltip" style="height:50px;"
                             src="{{ getThumbnailLockerDefault($locker->image) }}">
                    @else
                        --
                    @endif
                </p>
            </div>
            <div class="col-12 px-0 pb-3 d-block d-lg-flex d-md-flex">
                <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                    @lang('modules.lockers.status')</p>
                <p class="mb-0 text-dark-grey f-14 w-70">
                    @if ($locker->status == \App\Enums\LockerStatus::IN_USE)
                        <i class="fa fa-circle mr-1 text-light-green f-10"></i>{{  __('app.enums.lockerStatus.inUse') }}
                    @elseif($locker->status == \App\Enums\LockerStatus::AVAILABLE)
                        <i class="fa fa-circle mr-1 text-light-green f-10"></i>{{  __('app.enums.lockerStatus.available') }}
                    @elseif($locker->status == \App\Enums\LockerStatus::BROKEN)
                        <i class="fa fa-circle mr-1 text-red f-10"></i>{{  __('app.enums.lockerStatus.broken') }}
                    @else
                        <i class="fa fa-circle mr-1 text-red f-10"></i>{{  __('app.enums.lockerStatus.underMaintenance') }}
                    @endif
                </p>
            </div>
            <x-cards.data-row :label="__('modules.lockers.totalSlots')" :value="$slots->count() ?? '--'" />
            <x-cards.data-row :label="__('modules.locations.address')" :value="$location->description ?? '--'" />
            <x-cards.data-row :label="__('modules.license.code')" :value="$license->code ?? '--'" />
            <x-cards.data-row :label="__('modules.license.warrantyDuration')" :value="$license->warranty_duration ?? '--'" />
            <x-cards.data-row :label="__('app.createdAt')" :value="$locker->created_at ?? '--'" />
        </x-cards.data>
    </div>
    <div class="col-xl-5 col-lg-12 col-md-12">
        <x-cards.data :title="__('modules.lockers.lockerPerformance')">
            <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                @lang('modules.lockers.numBookings')</p>
            <x-bar-chart id="task-chart2" :chartData="$numBooking" height="300"  :spaceRatio="0.5" prefix="Month"></x-bar-chart>

            <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                @lang('modules.lockers.sumEarnings')</p>
            <x-bar-chart id="task-chart1" :chartData="$sumEarn" height="300"  :spaceRatio="0.5" prefix="Month"></x-bar-chart>
        </x-cards.data>
</div>
</div>
