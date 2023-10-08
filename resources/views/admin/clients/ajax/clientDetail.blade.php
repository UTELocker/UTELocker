<!-- ROW START -->
<div class="row mt-4">
    <div class="col-xl-7 col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4">
        <x-cards.data :title="__('modules.clients.clientInfo')">
            <x-cards.data-row :label="__('modules.clients.clientName')" :value="$client->name ?? '--'" />

            <x-cards.data-row :label="__('modules.clients.companyEmail')" :value="$client->email ?? '--'" />

            <x-cards.data-row :label="__('modules.clients.appName')" :value="$client->app_name ?? '--'" />

            <div class="col-12 px-0 pb-3 d-block d-lg-flex d-md-flex">
                <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                    @lang('modules.clients.companyLogo')</p>
                <p class="mb-0 text-dark-grey f-14 w-70">
                    @if ($client)
                        <img data-toggle="tooltip" style="height:50px;"
                             src="{{ getLogoDefault($client->logo) }}">
                    @else
                        --
                    @endif
                </p>
            </div>

            <x-cards.data-row :label="__('modules.clients.officePhoneNumber')" :value="$client->phone ?? '--'" />
            <x-cards.data-row :label="__('modules.clients.address')" :value="$client->address ?? '--'" />
            <x-cards.data-row :label="__('modules.clients.website')" :value="$client->website ?? '--'" />
            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">{{ __('app.language') }}</p>
                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap">
                    <span data-toggle="tooltip" data-original-title="{{ $client->locale }}" class="flag-icon flag-icon-gb flag-icon-squared"></span>
                    {{ $client->locale }}
                </p>
            </div>
        </x-cards.data>
    </div>
    {{--    <div class="col-xl-5 col-lg-12 col-md-12 ">--}}
    {{--        <div class="row">--}}
    {{--            <div class="col-md-12">--}}
    {{--                <x-cards.data :title="__('app.menu.projects')">--}}
    {{--                    <x-pie-chart id="project-chart" :labels="$projectChart['labels']" :values="$projectChart['values']"--}}
    {{--                                 :colors="$projectChart['colors']" height="250" width="300" />--}}
    {{--                </x-cards.data>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="row mt-4">--}}
    {{--            <div class="col-md-12">--}}
    {{--                <div class="card bg-white border-0 b-shadow-4">--}}
    {{--                    <x-cards.data :title="__('app.menu.invoices')">--}}
    {{--                        <x-pie-chart id="invoice-chart" :labels="$invoiceChart['labels']"--}}
    {{--                                     :values="$invoiceChart['values']" :colors="$invoiceChart['colors']" height="250"--}}
    {{--                                     width="300" />--}}
    {{--                    </x-cards.data>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
</div>
<!-- ROW END -->
