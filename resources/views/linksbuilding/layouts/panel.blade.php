<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', @$title) | {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;600;700;900&display=swap" rel="stylesheet">
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('debugadmin/assets/fontawesome-free/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('debugadmin/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="<?php echo Theme::url('website/build/public/css/icofont.min.css'); ?>">
    <link rel="stylesheet" href="{{asset('debugadmin/assets/vendors/iconfonts/ionicons/dist/css/ionicons.css')}}">
    <link rel="stylesheet" href="{{asset('debugadmin/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('debugadmin/assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('debugadmin/assets/vendors/css/vendor.bundle.addons.css')}}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('debugadmin/assets/css/shared/style.css')}}">
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('debugadmin/assets/css/demo_1/style.css')}}">
    <link rel="stylesheet" href="{{ asset('debugadmin/assets/css/new.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="<?php echo Theme::url('css/quill.snow.css'); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link rel="stylesheet" href="<?php echo Theme::url('css/bootstrap-colorpicker.css'); ?>">
    <!-- End Layout styles -->
    <link rel="stylesheet" href="{{asset('debugadmin/assets/css/custom.css')}}">
    @stack('stylescss')
    <!-- endinject -->
    {{--
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    --}}
    {{--<link rel="manifest" href="/site.webmanifest">--}}
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    @livewireStyles

</head>
<body>
<div id="app">
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->

    @livewire('topbar')
    <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->

        @include('includes.sidebar')
        <!-- partial -->
            <div class="main-panel">

            {{ @$slot }}

            <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
            @livewire('profile')
            <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="{{asset('debugadmin/assets/vendors/js/vendor.bundle.base.js')}}"></script>
<script src="{{asset('debugadmin/assets/vendors/js/vendor.bundle.addons.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="{{asset('debugadmin/assets/js/shared/off-canvas.js')}}"></script>
<script src="{{asset('debugadmin/assets/js/shared/misc.js')}}"></script>
<script src="{{asset('debugadmin/assets/fontawesome-free/js/all.js')}}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{asset('debugadmin/assets/js/demo_1/dashboard.js')}}"></script>
<script src="<?php echo Theme::url('/js/admin.js'); ?>"></script>

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.1/dist/alpine.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="<?php echo Theme::url('js/quill.js'); ?>"></script>
<script src="https://unpkg.com/quill-html-edit-button@2.1.0/dist/quill.htmlEditButton.min.js"></script>
<script src="<?php echo Theme::url('js/bootstrap-colorpicker.js'); ?>"></script>
<!-- End custom js for this page-->
@livewireScripts

@stack('scripts')

@yield('javascript')
</body>
</html>
