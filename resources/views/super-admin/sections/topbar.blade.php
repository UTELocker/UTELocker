<!-- HEADER START -->
<header class="main-header clearfix bg-white" id="header">
        <!-- NAVBAR LEFT(MOBILE MENU COLLAPSE) START-->
    <div class="navbar-left float-left d-flex align-items-center">
        <x-app-title class="d-none d-lg-flex" :pageTitle="$pageTitle"></x-app-title>

        <div class="d-block d-lg-none menu-collapse cursor-pointer position-relative" onclick="openMobileMenu()">
            <div class="mc-wrap">
                <div class="mcw-line"></div>
                <div class="mcw-line center"></div>
                <div class="mcw-line"></div>
            </div>
        </div>
    </div>

    <!-- NAVBAR LEFT(MOBILE MENU COLLAPSE) END-->
    <!-- NAVBAR RIGHT(SEARCH, ADD, NOTIFICATION, LOGOUT) START-->
    <div class="page-header-right float-right d-flex align-items-center justify-content-end">
        <ul>
            @if (!user()->isSuperUser())
                <li data-toggle="tooltip" data-placement="top" title="{{__('app.portal')}}" class="mr-2">
                    <div class="logout_box">
                        <a class="d-block header-icon-box" href="/portal" id="back-to-top">
                            <i class="fa fa-globe f-16"></i>
                        </a>
                    </div>
                </li>
                <li title="{{__('app.newNotifications')}}">
                    <div class="notification_box dropdown">
                        <a class="d-block dropdown-toggle header-icon-box show-user-notifications" type="link"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell f-16 text-dark-grey"></i>
                        </a>
                        <!-- DROPDOWN - INFORMATION -->
                        <div
                            class="dropdown-menu dropdown-menu-right notification-dropdown border-0 shadow-lg py-0 bg-additional-grey"
                            tabindex="0">
                            <div
                                class="d-flex px-3 justify-content-between align-items-center border-bottom-grey py-1 bg-white">
                                <div class="___class_+?50___">
                                    <p class="f-14 mb-0 text-dark f-w-500">@lang('app.newNotifications')</p>
                                </div>
                                <div class="f-12">
                                    <a href="javascript: maskAsRead('all')" id="actionNotification"
                                       class="text-dark-grey mark-notification-read  d-none">@lang('app.markRead')</a> |
                                    <a href="{{ route('admin.broken-lockers.index') }}"
                                       class="text-dark-grey">@lang('app.showAll')</a>
                                </div>
                            </div>
                            <div id="notification-list">

                            </div>
                        </div>
                    </div>
                </li>
            @endif
            <!-- LOGOUT START -->
            <li data-toggle="tooltip" data-placement="top" title="{{__('app.logout')}}">
                <div class="logout_box">
                    <a class="d-block header-icon-box" href="javascript:;" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off f-16 text-dark-grey"></i>
                    </a>
                </div>
            </li>
            <!-- LOGOUT END -->
        </ul>
    </div>
    <!-- NAVBAR RIGHT(SEARCH, ADD, NOTIFICATION, LOGOUT) START-->
</header>
<!-- HEADER END -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>

    const notificationUnread = [];
    const notificationRead = [];

    var pusher = new Pusher('{{ globalSettings()->pusher_app_key }}', {
        cluster: '{{ globalSettings()->pusher_app_cluster }}'
    });

    var channel = pusher.subscribe('notification'
        + ".{{ \App\Enums\NotificationType::LOCKER_BROKEN }}"
        + ".{{ user()->client_id }}"
        + ".{{ user()->id }}");

    channel.bind('pusher:subscription_succeeded', function(members) {
    });
    channel.bind('App\\Events\\NotificationProcessed', function(data) {
        const notification = data.notification;
        Swal.fire({
            icon: "info",
            text: notification.content,

            toast: true,
            position: "top-end",
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,

            customClass: {
                confirmButton: "btn btn-primary",
            },
            showClass: {
                popup: "swal2-noanimation",
                backdrop: "swal2-noanimation",
            },
        });
        notificationUnread.push(notification);
        renderBagde();
        renderNotificationList();
    });

    $.ajax({
        url: "{{ route('admin.notifications.index') }}",
        type: 'GET',
        success: function (data) {
            if (data.status === 'success') {
                data.data.data.forEach(function (notification) {
                    if (notification.status === '{{ \App\Classes\CommonConstant::DATABASE_NO }}') {
                        notificationUnread.push(notification);
                    } else {
                        notificationRead.push(notification);
                    }
                });
                console.log(notificationUnread);
                renderBagde();
                renderNotificationList();
            }
        }
    })

    function renderBagde() {
        const count = notificationUnread.length;
        if (count > 0) {
            const html =
            `<span
                class="badge badge-primary unread-notifications-count active-timer-count position-absolute"
            >
                ${count}
            </span>`;
            $('.show-user-notifications').append(html);
            $('#actionNotification').removeClass('d-none');
        } else {
            $('.show-user-notifications').find('.unread-notifications-count').remove();
            $('#actionNotification').addClass('d-none');
        }
    }

    function renderNotificationList() {
        let html = '';
        const allNotifications = notificationUnread.concat(notificationRead);
        allNotifications.forEach(function (notification) {
            const url = "{{ route('admin.broken-lockers.index') }}"
            const icon = notification.icon ? notification.icon : 'fa fa-bell';
            html +=
            `<a href="${url}" class="dropdown-item py-2">
                <div class="d-flex align-items-center">
                    <div class="mr-2">
                        <i class="${icon} f-16 text-dark-grey"></i>
                    </div>
                    <div class="w-100">
                        <p class="f-14 mb-0 text-dark f-w-500">Locker Broken</p>
                        <p class="f-12 mb-0 text-dark-grey">${notification.content}</p>
                        <p class="f-12 mb-0 text-dark-grey">${notification.created_at}</p>
                    </div>
                    <div class="mr-2">
                        <span class="badge badge-primary ${notification.status === '{{ \App\Classes\CommonConstant::DATABASE_YES }}' ? 'd-none' : ''}"
                            style="display: inline-block;"
                        ></span>
                    </div>

                </div>
            </a>`;
        });
        $('#notification-list').html(html);
    }

    function maskAsRead(id) {
        $.ajax({
            url: "{{ route('admin.notifications.update' , ['notification' => '/']) }}/" + id,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function (data) {
                if (data.status === 'success') {
                    notificationUnread.forEach(function (notification) {
                        notification.status = '{{ \App\Classes\CommonConstant::DATABASE_YES }}';
                    });
                    notificationRead.push(...notificationUnread);
                    notificationUnread.splice(0, notificationUnread.length);
                    renderBagde();
                    renderNotificationList();
                }
            }
        })
    }

</script>
