<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    {{--  Font Awesome Icons  --}}
    <link rel="stylesheet" href="{{ asset('vendor/css/font-awesome-icons/all.min.css') }}">

    @stack('datatable-styles')

    {{--  Template CSS  --}}
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('css/main.css') }}">

    <title>UTELocker</title>

    @stack('styles')

    <style>
        :root {
            --fc-border-color: #E8EEF3;
            --fc-button-text-color: #99A5B5;
            --fc-button-border-color: #99A5B5;
            --fc-button-bg-color: #ffffff;
            --fc-button-active-bg-color: #171f29;
            --fc-today-bg-color: #f2f4f7;
        }

        .fc a[data-navlink] {
            color: #99a5b5;
        }
        .ql-editor p{
            line-height: 1.42;
        }
        .ql-container .ql-tooltip {
            left: 8.5px !important;
            top: -17px !important;
        }
        .table [contenteditable="true"]{
            height: 55px;
        }
        .table [contenteditable="true"]:hover::after {
            content: "{{ __('app.clickEdit') }}" !important;
        }
        .table [contenteditable="true"]:focus::after {
            content: "{{ __('app.anywhereSave') }}" !important;
        }
        .table-bordered .displayName {
            padding: 17px;
        }
    </style>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
</head>
<body id="body">
    @includeIf('super-admin.sections.topbar')
    @include('sections.sidebar')

    <div class="body-wrapper clearfix">
        <section class="main-container bg-additional-grey mb-5 mb-sm-0" id="fullscreen">
            <div class="preloader-container d-flex justify-content-center align-items-center">
                <div class="spinner-border" role="status" aria-hidden="true"></div>
            </div>

            @yield('filter-section')

            <x-app-title class="d-block d-lg-none" pageTitle="UTELocker"></x-app-title>

            @yield('content')

        </section>
    </div>

    @include('sections.modals')

    <!-- Global Required Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
    <script>
        $(window).on('load', function () {
            $(".preloader-container").fadeOut("slow", function () {
                $(this).removeClass("d-flex");
            });
        });
    </script>
</body>
</html>
