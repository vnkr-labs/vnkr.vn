<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') — VNKR Admin</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=5" name="viewport">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}">

    <!-- Google Fonts — Work Sans (headings) + Be Vietnam Pro (body), Vietnamese subset -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Work+Sans:wght@400;500;600;700&subset=vietnamese&display=swap" rel="stylesheet">

    <!-- 1. VNKR Design Tokens (phải load TRƯỚC tất cả) -->
    <link rel="stylesheet" href="{{ asset('assets/css/vnkr-tokens.css') }}">

    <!-- 2. Arise Admin Theme -->
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/bootstrap/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.min.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

    <!-- 3. VNKR Admin Overrides (sau main.min.css để ghi đè) -->
    <link rel="stylesheet" href="{{ asset('assets/css/vnkr-admin.css') }}">

    <!-- Vendor Css Files -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/overlay-scroll/OverlayScrollbars.min.css') }}">

    @yield('styles')
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="page-wrapper">
        <!-- Site wrapper -->
        <div class="main-container">
            @include('admin.layouts.header')

            <!-- Left side column. contains the sidebar -->
            @include('admin.layouts.menu')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="alert">
                        <h4>@yield('title-page')</h4>
                    </div>
                </section>

                <!-- Main content -->
                @yield('main-content')
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            @include('admin.layouts.footer')

        </div>
        <!-- ./wrapper -->

        <!-- jQuery 3 -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/modernizr.js') }}"></script>
        <script src="{{ asset('assets/js/moment.js') }}"></script>

        <!-- Vendor Js Files -->
        <script src="{{ asset('assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/overlay-scroll/custom-scrollbar.js') }}"></script>

        <!-- Apex Charts -->
        <script src="{{ asset('assets/vendor/apex/apexcharts.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/apex/custom/sales/salesGraph.js') }}"></script>
        <script src="{{ asset('assets/vendor/apex/custom/sales/revenueGraph.js') }}"></script>
        <script src="{{ asset('assets/vendor/apex/custom/sales/taskGraph.js') }}"></script>

        <!-- Main Js Required -->
        <script src="{{ asset('assets/js/main.js') }}"></script>

        @yield('custom-js')

        {{-- Mobile sidebar toggle — Sprint 6 responsive --}}
        <script>
        (function () {
            var sidebar  = document.querySelector('.sidebar-wrapper');
            var toggle   = document.getElementById('toggle-sidebar');
            if (!sidebar || !toggle) return;

            // Tạo backdrop overlay
            var backdrop = document.createElement('div');
            backdrop.className = 'sidebar-backdrop';
            document.body.appendChild(backdrop);

            function openSidebar() {
                sidebar.classList.add('sidebar-open');
                backdrop.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('sidebar-open');
                backdrop.classList.remove('active');
                document.body.style.overflow = '';
            }

            toggle.addEventListener('click', function () {
                sidebar.classList.contains('sidebar-open') ? closeSidebar() : openSidebar();
            });

            backdrop.addEventListener('click', closeSidebar);

            // ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeSidebar();
            });
        })();
        </script>
    </div>
</body>

</html>
