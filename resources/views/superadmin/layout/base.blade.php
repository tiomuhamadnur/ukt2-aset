<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="TideUp">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('assets/img/ukt2logo.png') }}" />
    @yield('title-head')
    {{-- CSS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/daterange/daterange.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/chartist/css/chartist.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/chartist/css/chartist-custom.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" />
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

</head>

<body>

    <div id="loading-wrapper">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <!-- Include Header -->
    @include('superadmin.layout.header')

    <!-- Screen overlay start -->
    <div class="screen-overlay"></div>

    <div class="container-fluid">
        <!-- Navigation start -->
        <nav class="navbar navbar-expand-lg custom-navbar">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#UKT2"
                aria-controls="" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <i></i>
                    <i></i>
                    <i></i>
                </span>
            </button>
            <div class="collapse navbar-collapse" id="UKT2">
                <ul class="navbar-nav">
                    {{-- Include Navbar Dashboard --}}
                    @include('superadmin.layout.navbar')

                </ul>
            </div>
        </nav>

        {{-- Yield Path --}}
        @yield('path')

        <div class="main-container-scrollable">

            <div class="content-wrapper">

                {{-- Yield Content --}}
                @yield('content')
            </div>

        </div>
        {{-- Include Footer --}}
        @include('superadmin.layout.footer')

    </div>

    {{-- SCRIPT --}}
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/slimscroll/slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/slimscroll/custom-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/daterange/daterange.js') }}"></script>
    <script src="{{ asset('assets/vendor/daterange/custom-daterange.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('Helper/search.js') }}"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>



    @include('layout.modal_notif')
    @yield('javascript')
</body>

</html>
