<!-- ROW START -->
<div class="row">
    <!--  USER CARDS START -->
    <div class="col-xl-7 col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4 mb-md-0">
        <div class="row">
            @if ($user)
                <div class="col-xl-7 col-lg-6 col-md-6 mb-4 mb-lg-0">
                    <x-cards.user :image="$user->avatar">
                        <div class="row">
                            <div class="col-10">
                                <h4 class="card-title f-15 f-w-500 text-darkest-grey mb-0">
                                    {{ $user->name }}
                                    <span class="badge badge-{{ $user->status == \App\Enums\UserStatus::BAN ? 'danger' : 'success' }} f-12">
                                        {{
                                            $user->status == \App\Enums\UserStatus::BAN ? __('app.banned') : __('app.active')
                                        }}
                                    </span>
                                </h4>
                            </div>
                            <div class="col-2 text-right">
                                <div class="dropdown">
                                    <button class="btn f-14 px-0 py-0 text-dark-grey dropdown-toggle" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                                         aria-labelledby="dropdownMenuLink" tabindex="0">
                                        <a class="dropdown-item openRightModal"
                                           href="{{ route('admin.users.edit', $user->id) }}">@lang('app.edit')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="f-13 font-weight-normal text-dark-grey mb-0">
                            {{ __('modules.users.email') }} {{ ": " }} {{ $user->email }}
                        </p>
                        <p class="f-13 font-weight-normal text-dark-grey mb-0">
                            {{ __('modules.clients.clientName') }} {{ ": " }} {{ $client->name ?? '--' }}
                        </p>
                    </x-cards.user>
                </div>
            @endif
            <div class="col-xl-5 col-lg-6 col-md-6">
                <x-cards.widget :title="__('modules.profile.wallet')" :value="1"
                                icon="layer-group" />
            </div>
        </div>
    </div>
    <!--  USER CARDS END -->
</div>
<!-- ROW END -->

<!-- ROW START -->
<div class="row mt-4">
    <div class="col-xl-7 col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4">
        <x-cards.data :title="__('modules.users.profileInfo')">
            <x-cards.data-row :label="__('modules.users.name')" :value="$user->name ?? '--'" />

            <x-cards.data-row :label="__('modules.users.email')" :value="$user->email ?? '--'" />

            <x-cards.data-row :label="__('modules.clients.clientName')"
                              :value="$client->name ?? '--'" />

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
            @if ($client)
                <x-cards.data-row :label="__('modules.clients.companyEmail')"
                                  :value="$client->email ?? '--'" />
            @endif
            @if ($client)
                <x-cards.data-row :label="__('modules.clients.officePhoneNumber')"
                                  :value="$client->phone ?? '--'" />
            @endif
            @if ($client)
                <x-cards.data-row :label="__('modules.clients.website')"
                                  :value="$client->website ?? '--'" />
            @endif
            @if ($client)
                <x-cards.data-row :label="__('modules.clients.address')"
                                  :value="$client->address ?? '--'" />
            @endif
            <x-cards.data-row :label="__('modules.users.mobile')"
                              :value="$user->mobile ?? '--'" />

            <div class="col-12 px-0 pb-3 d-block d-lg-flex d-md-flex">
                <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                    @lang('modules.users.gender')</p>
                <p class="mb-0 text-dark-grey f-14 w-70">
                    <x-gender :gender='$user->gender' />
                </p>
            </div>

            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">{{ __('app.language') }}</p>
                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap">
                    <span data-toggle="tooltip" data-original-title="{{ $user->locale }}" class="flag-icon flag-icon-gb flag-icon-squared"></span>
                    {{ $user->locale }}
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
