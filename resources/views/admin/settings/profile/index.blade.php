@extends('layouts.app')
@section('content')
    <div class="w-100 d-flex">
        <x-settings.sidebar :activeMenu="$activeSettingMenu"/>
        <x-settings.card>
            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <nav class="tabs px-4 border-bottom-grey">
                        <div class="nav" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link f-15 active profile"
                               href="#" role="tab" aria-controls="nav-ticketAgents"
                               aria-selected="true">{{ __('modules.settings.menu.profile.profile') }}
                            </a>
                        </div>
                    </nav>
                </div>
            </x-slot>
            @include($view)
        </x-settings.card>
    </div>
@endsection
