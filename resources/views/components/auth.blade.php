<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{--  Font Awesome Icons  --}}
    <link rel="stylesheet" href="{{ asset('vendor/css/font-awesome-icons/all.min.css') }}">

    {{--  Template CSS  --}}
    <link href="{{ asset('vendor/froiden-helper/helper.css') }}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('css/main.css') }}">

    <title>Login</title>
    @stack('styles')

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    @include('sections.theme_css')
</head>
<body>
    <header class="sticky-top d-flex justify-content-center align-items-center login_header bg-white px-4">
        <img class="mr-2 rounded" src="https://namha-uat.svute.com/assets/images/logoDefault.png" alt="Logo"/>
        <h3 class="mb-0 pl-1">UTELocker</h3>
    </header>
    <section class="bg-grey py-5 login_section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="login_box mx-auto rounded bg-white text-center">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Global Required Javascript -->
    <script src="{{ asset('vendor/bootstrap/javascript/bootstrap-native.js') }}"></script>

    <!-- Font Awesome -->
    <script src="{{ asset('vendor/jquery/font-awesome-icons/all.min.js') }}"></script>

    <!-- Template JS -->
    <script src="{{ asset('js/main.js') }}"></script>

    {{ $scripts ?? '' }}
</body>
</html>
