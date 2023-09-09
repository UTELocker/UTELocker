<aside class="sidebar-dark">
    <div class="mobile-close-sidebar-panel w-100 h-100" onclick="closeMobileMenu()" id="mobile_close_panel"></div>
    <div class="main-sidebar" id="mobile_menu_collapse">
        <div class="sidebar-brand-box dropdown cursor-pointer">
            <div class="dropdown-toggle sidebar-brand d-flex align-items-center justify-content-between  w-100"
                 type="link" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="sidebar-brand-name">
                    <h1 class="mb-0 f-16 f-w-500 text-white-shade mt-0" data-placement="bottom" data-toggle="tooltip"
                        data-original-title="{{ $appName }}">{{ $appName }}
                        <i class="icon-arrow-down icons pl-2"></i>
                    </h1>
                    <div class="mb-0 position-relative pro-name">
                        <span class="bg-light-green rounded-circle"></span>
                        <p class="f-13 text-lightest mb-0" data-placement="bottom" data-toggle="tooltip"
                           data-original-title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="sidebar-brand-logo">
                    @if (isset(user()->client->logo))
                        <img src="{{ getLogoDefault(user()->client->logo)  }}">
                    @else
                        <img src="{{ getLogoDefault("")  }}">
                    @endif
                </div>
            </div>
            <div class="dropdown-menu dropdown-menu-right sidebar-brand-dropdown ml-3"
                 aria-labelledby="dropdownMenuLink" tabindex="0">
                <div class="d-flex justify-content-between align-items-center profile-box">
                    <a>
                        <div class="profileInfo d-flex align-items-center mr-1 flex-wrap">
                            <div class="profileImg mr-2">
                                <img class="h-100" src="https://namha-uat.svute.com/assets/images/logoDefault.png"
                                     alt="{{ Auth::user()->name }}">
                            </div>
                            <div class="ProfileData">
                                <h3 class="f-15 f-w-500 text-dark" data-placement="bottom" data-toggle="tooltip"
                                    data-original-title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</h3>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.show', Auth::user()->id) }}" data-toggle="tooltip"
                       data-original-title="{{ __('app.menu.profileSetting') }}">
                        <i class="far fa-edit"></i>
                    </a>
                </div>

                <a class="dropdown-item d-flex justify-content-between align-items-center f-15 text-dark"
                   href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                    @lang('app.logout')
                    <i class="fas fa-power-off"></i>
                </a>
            </div>
        </div>
        <div class="sidebar-menu" id="sideMenuScroll">
            @include('super-admin.sections.super-admin-menu')
        </div>
    </div>
    <div
        class="text-center d-flex justify-content-between align-items-center position-fixed sidebarTogglerBox">
        <button class="border-0 d-lg-block d-none text-lightest font-weight-bold" id="sidebarToggle"></button>

        <div class="d-flex align-items-center">
            <p class="mb-0 text-dark-grey px-1 py-0 rounded f-10">v1.0.0</p>
        </div>
    </div>
</aside>
