@extends('layouts.app')
@section('content')
    <div class="w-100 d-flex">
        <x-settings.sidebar :activeMenu="$activeSettingMenu"/>
        <x-settings.card>
            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <nav class="tabs px-4 border-bottom-grey">
                        <div class="nav" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link f-15 active siteGroupSettings"
                                href="{{ route('admin.siteGroupSettings.index') }}?tab=siteGroupSettings" role="tab" aria-controls="nav-ticketAgents"
                                aria-selected="true">{{ __('modules.settings.menu.site-group.site-group') }}
                            </a>
                            <a class="nav-item nav-link f-15 active policy"
                                href="{{ route('admin.siteGroupSettings.index') }}?tab=policy" role="tab" aria-controls="nav-ticketAgents"
                                aria-selected="true">{{ __('modules.settings.menu.site-group.policy') }}
                            </a>
                            <a class="nav-item nav-link f-15 active email"
                                href="{{ route('admin.siteGroupSettings.index') }}?tab=email" role="tab" aria-controls="nav-ticketAgents"
                                aria-selected="true">{{ __('modules.settings.menu.site-group.email') }}
                            </a>
                        </div>
                    </nav>
                </div>
            </x-slot>
            @include($view)
        </x-settings.card>
    </div>
@endsection
@push('scripts')
    <script>
        const tab = new URLSearchParams(window.location.search).get('tab');
        $('.nav-item').removeClass('active');
        const activeTab = "{{ $activeTab }}";
        $('.' + activeTab).addClass('active');
        $('#save-app-settings-site-group-form').click(function () {
            if (tab == 'policy') {
                const value = document.getElementById('description').children[0].innerHTML;
                document.getElementById('description-text').value = value;
            }
            const url = "{{ route('admin.siteGroupSettings.update', siteGroup()->id) }}";
            $.easyAjax({
                url: url,
                container: '#editSettings',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-app-settings-site-group-form",
                data: $('#editSettings').serialize(),
            })
        });
        if (tab == 'policy') {
            quillImageLoad('#description');
        }

    </script>
@endpush
