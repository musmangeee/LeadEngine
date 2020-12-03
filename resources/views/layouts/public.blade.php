<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sonido') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ mix('/vendor/fonts/fontawesome.css') }}">

    <!-- Styles -->
    <link href="{{ mix('css/public-style.css') }}" rel="stylesheet">
</head>
<body>

<!-- Layout wrapper -->
<div class="layout-wrapper layout-1 layout-without-sidenav">
    <div class="layout-inner">
        <!-- Layout navbar -->
        <nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center container-p-x bg-white" id="layout-navbar">
            <!-- Brand demo (see assets/css/demo/demo.css) -->
            <a href="/" class="navbar-brand router-link-active" target="_self"><img src="/images/logo.png" height="40"></a>
        </nav>
        <!-- / Layout navbar -->

        <!-- Layout container -->
        <div class="layout-container">

            <!-- Layout content -->
            <div class="layout-content">

                <!-- Content -->
                <div class="container-fluid flex-grow-1 container-p-y">
                    @yield('content')
                </div>
                <!-- / Content -->

                <!-- Layout footer -->
                {{--<nav class="layout-footer footer bg-white">
                    <div class="container-fluid d-flex flex-wrap justify-content-between text-center container-p-x pb-3">
                        <div class="pt-3">
                            <span class="footer-text font-weight-bolder">{{ env('APP_NAME') }}</span>
                        </div>
                    </div>
                </nav>--}}
                <!-- / Layout footer -->

            </div>
            <!-- Layout content -->

        </div>
        <!-- / Layout container -->

    </div>
</div>
<!-- / Layout wrapper -->
@yield('script')
</body>
</html>
