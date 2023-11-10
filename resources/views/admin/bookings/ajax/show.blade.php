@push('styles')
    <script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
@endpush
<div class="row mt-4">
    <div class="col-xl-7 col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4">
        <x-cards.data :title="__('app.show') . ' ' . __('modules.bookings.title')">
            <x-cards.data-row :label="__('modules.users.name')" :value="$booking->owner_name ?? '--'" />

            <x-cards.data-row :label="__('modules.lockers.code')" :value="$booking->locker_code ?? '--'" />

            <div class="col-12 px-0 pb-3 d-block d-lg-flex d-md-flex">
                <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                    @lang('modules.bookings.status')</p>
                <p class="mb-0 text-dark-grey f-14 w-70">
                    @include('admin.bookings.status', ['row' => $booking])
                </p>
            </div>
            <x-cards.data-row :label="__('modules.lockers.code')" :value="$booking->locker_code ?? '--'" />
            <x-cards.data-row :label="__('modules.lockerSLots.code')" :value="$slotCode ?? '--'" />
            <x-cards.data-row :label="__('modules.locations.address')" :value="$booking->address ?? '--'" />
            <x-cards.data-row :label="__('modules.bookings.startDate')" :value="$booking->start_date ?? '--'" />
            <x-cards.data-row :label="__('modules.bookings.endDate')" :value="$booking->end_date ?? '--'" />
            <x-cards.data-row :label="__('modules.transactions.amount')" :value="$booking->total_price ?? '--'" />
            <div class="col-12 px-0 pb-3 d-block d-lg-flex d-md-flex">
                <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                    @lang('modules.transactions.reference')</p>
                <a class="mb-0 f-14 w-70" href="{{ route('admin.payment.transaction.show', $booking->transaction_reference) }}">
                    {{ $booking->transaction_reference ?? '--' }}
                </a>
            </div>
            <x-cards.data-row :label="__('app.createdAt')" :value="$booking->created_at ?? '--'" />
        </x-cards.data>
    </div>
    <div class="col-xl-5 col-lg-12 col-md-12">
        <x-cards.data :title="__('app.show') . ' ' . __('modules.histories.booking')">
            <div class="recent-ticket position-relative" data-menu-vertical="1" data-menu-scroll="1"
                 data-menu-dropdown-timeout="500" id="recentTickets">
                <div class="recent-ticket-inner position-relative">
                    @foreach ($historyLine as $item)
                        <div class="r-t-items d-flex">
                            <div class="r-t-items-left text-lightest f-21 mr-2">
                                <i class="fa fa-ticket-alt"></i>
                                <div
                                        style="
                                            position: relative;
                                            background: #99a5b5;
                                            width: 2px;
                                            height: 100%;
                                            align-items: center;
                                            top: -15px;
                                            left: 44%;"
                                    ></div>
                                </div>
                            <div class="r-t-items-right ">
                                <h3 class="f-14 font-weight-bold">
                                    <a class="text-dark"
                                       href="">{{ $item['subject'] }}</a>
                                </h3>
                                <span class="d-flex mb-1">
                                    @if ($item['status'] == 'cancelled')
                                        @php
                                            $statusColor = 'red';
                                        @endphp
                                    @elseif($item['status'] == 'created')
                                        @php
                                            $statusColor = 'yellow';
                                        @endphp
                                    @elseif($item['status'] == 'start')
                                        @php
                                            $statusColor = 'green';
                                        @endphp
                                    @elseif($item['status'] == 'end')
                                        @php
                                            $statusColor = 'blue';
                                        @endphp
                                    @endif
                                        <span class="f-13 text-darkest-grey text-capitalize">
                                            <i class="fa fa-circle mr-1 f-10" style="color: {{ $statusColor }};"></i>{{ $item['status'] }}
                                        </span>

                                    </span>
                                <p class="f-12 text-dark-grey">
                                    {{ $item['date'] }}
                                </p>
                            </div>
                        </div><!-- item end -->
                    @endforeach

                </div>
            </div>
        </x-cards.data>
    </div>
</div>
