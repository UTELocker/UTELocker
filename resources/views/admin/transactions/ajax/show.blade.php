@push('styles')
    <script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
@endpush
<div class="row mt-4">
    <div class="col-xl-7 col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4">
        <x-cards.data :title="__('app.show') . ' ' . __('modules.bookings.title')">
            <x-cards.data-row :label="__('modules.transactions.reference')" :value="$transaction->reference ?? '--'" />
            @if (user()->isSuperUser())
                <div class="col-12 px-0 pb-3 d-block d-lg-flex d-md-flex">
                    <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                        @lang('modules.clients.clientName')</p>
                    <a class="mb-0 f-14 w-70" href="{{ route('admin.clients.show', $transaction->client->id) }}">
                        {{ $transaction->client->name ?? '--' }}
                    </a>
                </div>
            @endif

            <x-cards.data-row
                :label="__('modules.transactions.type')"
                :value="\App\Enums\TransactionType::getDescription($transaction->type) ?? '--'"
            />

            <x-cards.data-row
                :label="__('modules.paymentMethod.name')"
                :value="$transaction->paymentMethod->name ?? '--'"
            />

            <div class="col-12 px-0 pb-3 d-block d-lg-flex d-md-flex">
                <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                    @lang('modules.bookings.status')</p>
                <p class="mb-0 text-dark-grey f-14 w-70">
                    @include('admin.transactions.status', ['row' => $transaction])
                </p>
            </div>
            @if ($transaction->booking->count() > 0)
                <div class="col-12 px-0 pb-3 d-block d-lg-flex d-md-flex">
                    <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                        @lang('modules.transactions.booking')</p>
                    <p class="mb-0 text-dark-grey f-14 w-70">
                        @foreach($transaction->booking as $key => $booking)
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" style="display: block">
                                {{ 'Slots ' . $key+1  }}
                                {{ ' - ' }}
                                {{ $booking->locker->code ?? '--' }}
                            </a>
                        @endforeach
                    </p>
                </div>
            @endif
            <x-cards.data-row :label="__('modules.transactions.amount')" :value="number_format($transaction->amount) ?? '--'" />
            <x-cards.data-row :label="__('modules.transactions.balance')" :value="number_format($transaction->balance) ?? '--'" />
            <x-cards.data-row :label="__('modules.transactions.promotion')" :value="number_format($transaction->promotion_balance) ?? '--'" />
            <x-cards.data-row :label="__('modules.transactions.content')" :value="$transaction->content ?? '--'" />
            <x-cards.data-row :label="__('modules.transactions.createdAt')" :value="$transaction->time ?? '--'" />
        </x-cards.data>
    </div>
</div>
