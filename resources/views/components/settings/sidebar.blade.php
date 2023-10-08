<div class="mobile-close-overlay w-100 h-100" id="close-settings-overlay"></div>
<div class="settings-sidebar bg-white py-3" id="mob-settings-sidebar">
    <a class="d-block d-lg-none close-it" id="close-settings"><i class="fa fa-times"></i></a>
    <form class="border-bottom-grey px-4 pb-3 d-flex">
        <div class="input-group rounded py-1 border-grey">
            <div class="input-group-prepend">
                <span class="input-group-text border-0 bg-white">
                    <i class="fa fa-search f-12 text-lightest"></i>
                </span>
            </div>
            <input
                type="text"
                id="search-setting-menu"
                class="form-control border-0 f-14 pl-0"
                placeholder="@lang('app.search')"
            >
        </div>
    </form>
    <ul class="settings-menu" id="settingsMenu">
        @if (user()->hasPermission(\App\Enums\UserRole::SUPER_USER))
            <x-settings.menu-item
                :active="$activeMenu" menu="settings-app"
                :href="route('admin.settings.index')"
                :text="__('modules.settings.menu.app.menu')"
            />
        @endif
        @if (user()->hasPermission(\App\Enums\UserRole::ADMIN))
            <x-settings.menu-item
                :active="$activeMenu" menu="settings-site-group"
                :href="route('admin.siteGroupSettings.index')"
                :text="__('modules.settings.menu.site-group.menu')"
            />
        @endif
        <x-settings.menu-item
            :active="$activeMenu" menu="settings-profile"
            :href="route('admin.profileSettings.index')"
            :text="__('modules.settings.menu.profile.menu')"
        />
    </ul>
</div>

<script>
    $("body").on("click", ".ajax-tab", function (event) {
        event.preventDefault();

        $('.project-menu .p-sub-menu').removeClass('active');
        $(this).addClass('active');

        const requestUrl = this.href;

        $.easyAjax({
            url: requestUrl,
            blockUI: true,
            container: ".content-wrapper",
            historyPush: true,
            success: function (response) {
                if (response.status === "success") {
                    $('.content-wrapper').html(response.html);
                    UTELocker.common.init('.content-wrapper');
                }
            }
        });
    });

    $("#search-setting-menu").on("keyup", function () {
        const value = this.value.toLowerCase().trim();
        $("#settingsMenu li").show().filter(function () {
            return $(this).text().toLowerCase().trim().indexOf(value) === -1;
        }).hide();
    });
</script>
