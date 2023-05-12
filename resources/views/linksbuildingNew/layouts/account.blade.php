<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
	<link href="<?php echo Theme::url('dashboard/css/all.min.css'); ?>" rel="stylesheet"/>
	<link href="<?php echo Theme::url('css/animate.min.css'); ?>" rel="stylesheet"/>
	<link href="<?php echo Theme::url('css/datepicker.min.css'); ?>" rel="stylesheet"/>
	<link href="<?php echo Theme::url('css/account.css'); ?>" rel="stylesheet"/>
	<link href="<?php echo Theme::url('css/quill.snow.css'); ?>" rel="stylesheet"/>
	<link href="<?php echo Theme::url('debugadmin/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css'); ?>" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
	<link href="<?php echo Theme::url('css/override.css'); ?>" rel="stylesheet"/>
	@stack('stylescss')
	@livewireStyles
</head>
<body>
	<div class="container-scroller">
		<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row shadow">
			<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center d-none d-lg-block" style="background-color: #eee;">
				<a class="navbar-brand">
					<img src="{{ theme_url('img/logo.png') }}" alt="" id="admin_logo">
					<img src="{{ theme_url('img/logo_res.png') }}" alt="" id="admin_ico">
				</a>
			</div>
			<div class="navbar-menu-wrapper d-flex align-items-stretch">
				<button class="navbar-toggler navbar-toggler align-self-center header__burger" type="button" data-toggle="minimize">
					<span></span>
					<span></span>
					<span></span>
				</button>
				<ul class="navbar-nav navbar-nav-right">
					<li class="nav-item d-none d-lg-block full-screen-link">
						@livewire('cart.link')
					</li>
					<li class="nav-item full-screen-link dropdown language-dropdown">
						<a class="nav-link dropdown-toggle px-2 d-flex align-items-center border-gray mx-0 px-2" id="LanguageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
							@if(App::getLocale() == 'nl')
								<div class="d-inline-flex mr-0 mr-md-3">
									<div class="flag-icon-holder">
										<i class="flag-icon flag-icon-nl"></i>
									</div>
								</div>
								<span class="profile-text font-weight-medium d-none d-md-block">{{__('Ned')}}</span>
							@endif
							@if(App::getLocale() == 'en')
								<div class="d-inline-flex mr-0 mr-md-3">
									<div class="flag-icon-holder">
										<i class="flag-icon flag-icon-us"></i>
									</div>
								</div>
								<span class="profile-text font-weight-medium d-none d-md-block">{{__('Eng')}}</span>
							@endif
						</a>
						<div class="dropdown-menu dropdown-menu-left navbar-dropdown py-2" aria-labelledby="LanguageDropdown">
							<a href="{{ LaravelLocalization::getLocalizedURL('nl', null, [], true) }}" class="dropdown-item d-flex">
								<div class="flag-icon-holder mr-2">
									<i class="flag-icon flag-icon-nl"></i>
								</div>
								{{__('Ned')}}
							</a>
							<a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" class="dropdown-item d-flex">
								<div class="flag-icon-holder mr-2">
									<i class="flag-icon flag-icon-us"></i>
								</div>
								{{__('Eng')}}
							</a>
						</div>
					</li>
					<li class="nav-item nav-profile dropdown">
						<div id="header-profile" class="nav-profile-img d-flex align-items-center">
							
								<?php $profile = auth()->user()->profile_image; ?>
								<?php if (!empty($profile)): ?>
								<img src="{{ asset('storage/profile')."/".auth()->user()->profile_image }}" alt="profile" class="img-fluid" >
								<?php else: ?>
								<img class="rounded-circle img-fluid" src="{{asset('/debugadmin/assets/images/faces/face8.jpg')}}" alt="{{__('Profile image')}}">
								<?php endif; ?>		
							<p class="mb-1 text-black ml-2">{{ auth()->user()->name." ".auth()->user()->lastname }}</p>
						</div>	
					</li>
					<li class="nav-item nav-profile dropdown">
						<button class="nav-link btn btn-light px-2 mx-1" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
							<i class="fas fa-ellipsis-h text-dark"></i>
						</button>
						<div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
							<a class="dropdown-item" href="{{ route('customer_profile') }}">{{__('Profile')}}</a>
							<a class="dropdown-item" href="{{ route('customer_settings') }}">{{__('Settings')}}</a>
							<div class="dropdown-divider"></div>
							<a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Sign out')}}</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
							</form>
						</div>
					</li>
				</ul>
				<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center header__burger ml-2" type="button" data-toggle="offcanvas">
					<span></span>
					<span></span>
					<span></span>
				</button>
			</div>
		</nav>
		<div class="container-fluid page-body-wrapper">
			<nav class="sidebar sidebar-offcanvas" id="sidebar">
				<ul class="nav">					
					<li class="nav-item nav-profile">
						<a href="#" class="nav-link">
							<div class="nav-profile-image">
								<?php $profile = auth()->user()->profile_image; ?>
								<?php if (!empty($profile)): ?>
								<img src="{{ asset('storage/profile')."/".auth()->user()->profile_image }}" alt="profile">
								<?php else: ?>
								<img  src="{{asset('/debugadmin/assets/images/faces/face8.jpg')}}" alt="{{__('Profile image')}}">
								<?php endif; ?>
								<span class="login-status online"></span>
							</div>
							<div class="nav-profile-text d-flex flex-column">
								<span class="font-weight-bold mb-2">{{ auth()->user()->name." ".auth()->user()->lastname }}</span>
								<span class="text-small">{{ auth()->user()->roles->description }}</span>
							</div>
						</a>
					</li>
					<li class="nav-item d-flex align-items-center @if(@$menu == 'Dashboard') active @endif">			
						<a class="nav-link pl-4" href="{{ route('customer_dashboard') }}">
							<i class="fa-light fa-house"></i>
							<span class="menu-title">{{__('Dashboard')}}</span>
						</a>
					</li>
					<li class="nav-item d-flex align-items-center @if(@$menu == 'Buy links') active @endif">
						<a class="nav-link pl-4" href="{{ route('customer_buylinks') }}">
							<i class="fa-light fa-box-dollar"></i>
							<span class="menu-title">{{__('Buy links')}}</span>
						</a>
					</li>
					<li class="nav-item d-flex align-items-center @if(@$menu == 'Add in bulk') active @endif">
						<a class="nav-link pl-4" href="{{ route('customer_addbulk') }}">
							<i class="fa-light fa-boxes-stacked"></i>
							<span class="menu-title">{{__('Add bulk')}}</span>
						</a>
					</li>
					<li class="nav-item d-flex align-items-center @if(@$menu == 'My links') active @endif">
						<a class="nav-link pl-4" href="{{ route('customer_links') }}">
							<i class="fa-light fa-box-check"></i>
							<span class="menu-title">{{__('My links')}}</span>
						</a>
					</li>
					<li class="nav-item d-flex align-items-center @if(@$menu == 'Packages') active @endif">
						<a class="nav-link pl-4" href="{{ route('customer_packages') }}">
							<i class="fa-light fa-box-archive"></i>
							<span class="menu-title">{{__('Packages')}}</span>
						</a>
					</li>
					<li class="nav-item d-flex align-items-center @if(@$menu == 'Orders') active @endif">
						<a class="nav-link pl-4" href="{{ route('customer_orders') }}">
							<i class="fa-light fa-clipboard-check"></i>
							<span class="menu-title">{{__('Orders')}}</span>
						</a>
					</li>
					<li class="nav-item d-flex align-items-center @if(@$menu == 'Support') active @endif">
						<a class="nav-link pl-4" href="{{ route('customer_support') }}">
							<i class="fa-light fa-circle-info"></i>
							<span class="menu-title">{{__('Support')}}</span>
						</a>
					</li>
				</ul>
			</nav>
			<div class="main-panel">
				<div class="content-wrapper">
					<main role="main">
						@yield('content', @$slot)
					</main>
				</div>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.1/dist/alpine.min.js" defer></script>
	<script src="<?php echo Theme::url('js/popper.min.js'); ?>"></script>
	<script src="<?php echo Theme::url('js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo Theme::url('js/holder.min.js'); ?>"></script>
	<script src="<?php echo Theme::url('js/datepicker.min.js'); ?>"></script>
	<script src="<?php echo Theme::url('js/dialog.js'); ?>"></script>
	<script src="<?php echo Theme::url('js/quill.js'); ?>"></script>
	<script src="<?php echo Theme::url('js/account.js'); ?>"></script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	@livewireScripts
	@stack('scripts')
	@yield('javascript')
</body>
</html>
