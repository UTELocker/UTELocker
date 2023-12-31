<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(globalSettings()->favicon) }}">
    <title>{{ $pageTitle }}</title>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset(globalSettings()->favicon) }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="{{mix('css/portalApp.css')}}"/>
</head>
<body>
    <div id="portalApp"></div>
    <script>
        window.user = {!! json_encode(auth()->user()) !!};
        window.settings = {!! json_encode($siteGroupSettings) !!};
        window.configFireBase = {!! json_encode($configFireBase) !!};
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script type="module" src="{{mix('js/portalMain.js')}}"></script>
</body>
</html>
