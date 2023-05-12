<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- CSRF Token -->
		<title>{{__('HOME')}} | {{ config('app.name', 'Laravel') }}</title>

		<!-- Bootstrap core CSS -->
		<link href="<?php echo Theme::url('css/bootstrap.min.css'); ?>" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600;700&amp;display=swap" rel="stylesheet" />
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
		<link href="<?php echo Theme::url('css/animate.min.css'); ?>" rel="stylesheet" />
		<link href="<?php echo Theme::url('css/home.css'); ?>" rel="stylesheet" />
	</head>
	<body>
		<header>
			<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-blue">
				<div class="container">
					{{-- <a class="navbar-brand logo-text text-uppercase" href="{{ route('customer_dashboard') }}"><img src="{{ asset('images/logo.svg')}}" class="logo-svg" /> {{ env('APP_NAME') }}</a> --}}
					<button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarCollapse">
						<ul class="navbar-nav ml-auto">
							<li class="nav-item">
								<a class="btn nav-link px-4 py-2" href="{{ route('index') }}">{{__('Home')}}</a>
							</li>
							<li class="nav-item">
								<a class="btn nav-link px-4 py-2" href="#">{{__('Contact')}}</a>
							</li>
							<li class="nav-item">
								<a class="btn nav-link px-4 py-2" href="{{ url('/login') }}">{{__('Login')}}</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>

		<section class="section bg-gray pb-0 p-first">
			<div class="container">
				<div class="row justify-content-center text-center">
					<div class="col-md-12">
						<h1 class="mb-4">{{__('Koop links en blogs consectetuer adipiscing')}}</h1>
						<h3 class="text-aqua">{{__('Het meest complete backlinkplatform') }}</h3>
					</div>
					<div class="col-md-auto col-lg-auto row my-4">
						<div class="col-md-6 col-lg p-2">
							<a class="btn btn-lg btn-aqua" href="{{ url('/login') }}">
								{{__('Inloggen')}}
							</a>
						</div>
						<div class="col-md-6 col-lg p-2">
							<a class="btn btn-lg btn-aqua" href="{{ url('/register') }}">
								{{__('Registreer')}}
							</a>
						</div>
					</div>
					<div class="col-lg-9">
						<img id="mac" class="img-fluid" src="{{ theme_url('images/home/mac.png') }}" alt="">
					</div>
				</div>
			</div>
		</section>

		<section class="section">
			<div class="container">
				<div class="row justify-content-center text-center">
					<div class="col-lg-12 mb-5">
						<h1 class="mb-4">{{__('Hoe werkt het?')}}</h1>
						<h6>{{ __('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.')}}</h6>
					</div>
					<div class="col-md-6 col-lg-4 mb-4">
						<div class="card bg-services">
							<div class="card-body">
								<img class="img-fluid" height="100" src="{{ theme_url('images/home/charts.png') }}" alt="charts">
								<h5 class="my-4">{{__('Zoek een startpagina')}}</h5>
								<p>{{ __('Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt.')}}</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-4 mb-4">
						<div class="card bg-services">
							<div class="card-body">
								<img class="img-fluid" height="100" src="{{ theme_url('images/home/links.png') }}" alt="charts">
								<h5 class="my-4">{{__('Kies je link')}}</h5>
								<p>{{ __('Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt.')}}</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-4 mb-4">
						<div class="card bg-services">
							<div class="card-body">
								<img class="img-fluid" height="100" src="{{ theme_url('images/home/cart-shop.png') }}" alt="charts">
								<h5 class="my-4">{{__('Betalen')}}</h5>
								<p>{{ __('Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt.')}}</p>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</section>

		<section class="section bg-gray">
			<div class="container">
				<div class="row justify-content-center text-center">
					<div class="col-lg-12">
						<h1>{{__('Aenean vulputate eleifend tellus')}}</h1>
						<h1 class="mb-4">{{__('Aenean leo ligula, porttitor eu, consequat vitae')}}</h1>
						<p>{{ __(' Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing.')}}</p>
						<ul class="list-unstyled ul-list">
							<li>
								<i class="fas fa-check text-aqua mr-2"></i>
								{{ __('Cum sociis natoque penatibus et magnis dis parturient montes.') }}
							</li>
							<li>
								<i class="fas fa-check text-aqua mr-2"></i>
								{{ __('Donec quam felis, ultricies nec, pellentesque eu, pretium.') }}
							</li>
							<li>
								<i class="fas fa-check text-aqua mr-2"></i>
								{{ __('Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. ') }}
							</li>
							<li>
								<i class="fas fa-check text-aqua mr-2"></i>
								{{ __('In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. ') }}
							</li>
							<li>
								<i class="fas fa-check text-aqua mr-2"></i>
								{{ __('Cras dapibus. Vivamus elementum semper nisi.') }}
							</li>
							<li>
								<i class="fas fa-check text-aqua mr-2"></i>
								{{ __('Aenean vulputate eleifend tellus. Aenean leo ligula.') }}
							</li>
						</ul>
					</div>
					<div class="col-lg-10">
						<img class="img-fluid" src="{{ theme_url('images/home/service.png') }}" alt="service">
					</div>
				</div>
				
			</div>
		</section>

		<section class="section p-100 bg-aqua">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-lg-auto text-center text-md-center text-lg-left text-xl-left">
						<h1 class="text-white">{{__('Probeer het nu!')}}</h1>
						<h2 class="text-white">{{__('Registreren op ons platform is helemaal gratis.')}}</h2>
						<h2 class="text-white">{{__('Aarzel dus niet om een kijkje te nemen!')}}</h2>
					</div>
					<div class="col-lg-auto d-flex align-items-center justify-content-center">
						<a class="btn btn-lg btn-white my-4" href="{{ url('/register') }}">{{__('Registreer nu')}}</a>
					</div>
				</div>
			</div>
		</section>

		<footer class="bg-blue">
			<div class="container section p-50">
				<div class="row m-0 justify-content-center justify-content-lg-between text-white">
					<div class="col-10 col-md-5 col-lg-4">
						<p>{{__('Linken')}}</p>
						<p>{{__('Bespaar tot wel 60% van jouw tijd op jouw linkbuilding door gebruik te maken van onze tool. ')}}</p>
					</div>
					<div class="col-10 col-md-4 col-lg-3 d-flex align-items-center">
						<ul class="list-unstyled p-10">
							<li>
								<i class="fas fa-chevron-right"></i>
								<i class="fas fa-chevron-right mr-2"></i>
								{{__('Dashboard')}}
							</li>
							<li>
								<i class="fas fa-chevron-right"></i>
								<i class="fas fa-chevron-right mr-2"></i>
								{{__('My account')}}
							</li>
							<li>
								<i class="fas fa-chevron-right"></i>
								<i class="fas fa-chevron-right mr-2"></i>
								{{__('Help Center')}}
							</li>
							<li>
								<i class="fas fa-chevron-right"></i>
								<i class="fas fa-chevron-right mr-2"></i>
								{{__('Ask for support')}}
							</li>
						</ul>
					</div>
					<div class="col-10 col-md-3 col-lg-3">
						<ul class="list-unstyled p-10">
							<li>
								{{__('Legal stuff')}}
							</li>
							<li>
								{{__('Privacy Policy')}}
							</li>
							<li>
								{{__('Terms and conditions')}}
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="container-fluid footer-botton text-center">
				<p class="m-0">{{__('All rights reserved Copyright ') }} © {{ date("Y") }}</p>
			</div>
		</footer>

		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.1/dist/alpine.min.js" defer></script>
		<script src="<?php echo Theme::url('js/popper.min.js'); ?>"></script>
		<script src="<?php echo Theme::url('js/bootstrap.min.js'); ?>"></script>
	</body>
</html>
