
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', @$title) | {{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo Theme::url('css/tailwind.css'); ?>" rel="stylesheet">
    <link href="<?php echo Theme::url('css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="<?php echo Theme::url('css/animate.min.css'); ?>" rel="stylesheet" />
    <link href="<?php echo Theme::url('css/datepicker.min.css'); ?>" rel="stylesheet" />
    <link href="<?php echo Theme::url('css/account.css'); ?>" rel="stylesheet" />
    <link href="<?php echo Theme::url('css/quill.snow.css'); ?>" rel="stylesheet" />
    @stack('stylescss')
    @livewireStyles
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
        <a class="navbar-brand logo-text text-uppercase" href="{{ route('customer_dashboard') }}"><img src="{{ theme_url('images/logo.svg')}}" class="logo-svg" /> {{ env('APP_NAME') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">           
            <ul class="navbar-nav mr-auto">
                <li class="nav-item @if(@$menu == 'Dashboard') active @endif">
                    <a class="nav-link" href="{{ route('customer_dashboard') }}">{{__('Dashboard')}}</a>
                </li>
                <li class="nav-item @if(@$menu == 'Buy links') active @endif">
                    <a class="nav-link" href="{{ route('customer_buylinks') }}">{{__('Buy links')}}</a>
                </li>
                <li class="nav-item @if(@$menu == 'My links') active @endif">
                    <a class="nav-link" href="{{ route('customer_links') }}">{{__('My links')}}</a>
                </li>
                <li class="nav-item @if(@$menu == 'Packages') active @endif">
                    <a class="nav-link" href="{{ route('customer_packages') }}">{{__('Packages')}}</a>
                </li>
                <li class="nav-item @if(@$menu == 'Orders') active @endif">
                    <a class="nav-link" href="{{ route('customer_orders') }}">{{__('Orders')}}</a>
                </li>
                <li class="nav-item @if(@$menu == 'Support') active @endif">
                    <a class="nav-link" href="{{ route('customer_support') }}">{{__('Support')}}</a>
                </li>
            </ul>
            <div class="form-inline">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        @livewire('cart.link')
                    </li>
                    <li class="nav-item float-right">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" type="button" id="dropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} {{ Auth::user()->lastname }}</a>
                            <div class="dropdown-menu" aria-labelledby="dropdownProfile">
                                <a class="dropdown-item" href="{{ route('customer_profile') }}">{{__('Profile')}}</a>
                                <a class="dropdown-item" href="{{ route('customer_settings') }}">{{__('Settings')}}</a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Sign out')}}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main role="main">
    @yield('content', @$slot)
</main>


<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.1/dist/alpine.min.js" defer></script>
<script src="<?php echo Theme::url('js/popper.min.js'); ?>"></script>
<script src="<?php echo Theme::url('js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo Theme::url('js/holder.min.js'); ?>"></script>
<script src="<?php echo Theme::url('js/datepicker.min.js'); ?>"></script>
<script src="<?php echo Theme::url('js/dialog.js'); ?>"></script>
<script src="<?php echo Theme::url('js/quill.js'); ?>"></script>
<script src="<?php echo Theme::url('js/account.js'); ?>"></script>
@livewireScripts

@stack('scripts')

@yield('javascript')
</body>
</html>
