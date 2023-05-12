<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
		{{-- <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600;700&amp;display=swap" rel="stylesheet" />
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
		<link href="<?php echo Theme::url('css/animate.min.css'); ?>" rel="stylesheet" />
		<link href="<?php echo Theme::url('css/owl.carousel.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo Theme::url('css/home.css'); ?>" rel="stylesheet" />
		<link href="<?php echo Theme::url('css/general.min.css'); ?>" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">  --}}

		<link rel="stylesheet" href="<?php echo Theme::url('css/bootstrap_home.min.css');?>">
		<link rel="stylesheet" href="<?php echo Theme::url('css/all.min.css');?>">
		<link rel="stylesheet" href="<?php echo Theme::url('css/aos.css');?>">
		<link rel="stylesheet" href="<?php echo Theme::url('css/style.css');?>">

		<link href="<?php echo Theme::url('css/addons.css'); ?>" rel="stylesheet">
	</head>
	<body>
		<header>
		  <nav class="navbar navbar-expand-lg navbar">
			<div class="container">
			  <a class="navbar-brand" href="{{ route('dashboard') }}">
				<img src="{{ theme_url('img/logo.png') }}" alt="">
			  </a>
			  <button class="navbar-toggler ms-auto collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<i class="fa-solid fa-horizontal-rule"></i>
				<i class="fa-solid fa-horizontal-rule"></i>
				<i class="fa-solid fa-horizontal-rule"></i>
			  </button>
			  <div class="collapse navbar-collapse" id="navbarNav">
				<ul id="main-manu" class="navbar-nav">
				  <li class="nav-item">
					<a href="#" class="nav-link">
						{{__('Services')}}
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" class="nav-link">
						{{__('About us')}}
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" class="nav-link">
						{{__('Contact')}}
					</a>
				  </li>
				</ul>
			  </div>
			</div>
		  </nav>
		</header>

		<section id="section-one" class="section section--hero">
		  <figure class="figure">
			<img src="{{ theme_url('img/image-one.png') }}" alt="">
		  </figure>
		  <figure class="decoration">
			<img src="{{ theme_url('img/decoration.png') }}" alt="">
		  </figure>
		  <div class="container">
			<div class="row">
			  <div class="col-lg-6">
				<div class="content" data-aos="fade-right">
				  <h1 class="title__main">
					@dump("here")
					{{__('The most complete')}} <br>
					{{__('backlink path')}}
				  </h1>
				  <p class="paragraph">
					{{__('Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut.')}}
				  </p>
				  <div class="buttons">
					<a href="{{ route('login') }}" class="btn btn-primary">
						{{__('Login')}}
					</a>
					<a href="{{ route('register') }}" class="btn btn-secondary">
					  {{__('Register')}}
					</a>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</section>

		<section class="section section--blocks">
		  <div class="container">
			<h3 class="title__section text-center" data-aos="fade-up">
				{{__('How does it work?')}}
			</h3>
			<div class="row">
			  <div class="col-lg-4">
				<div class="block" data-aos="fade-up">
				  <h3 class="title__block">
					{{__('Search a starting page')}}
				  </h3>
				  <figure class="block__figure">
					<img src="{{ theme_url('img/icon1.png') }}" alt="" class="block__figure__image">
				  </figure>
				  <p class="paragraph">
					{{__('Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean  imperdiet. Etiam ultricies nisi vel augue.')}}
				  </p>
				</div>
			  </div>
			  <div class="col-lg-4">
				<div class="block" data-aos="fade-up">
				  <h3 class="title__block">
					{{__('Chose your link')}}
				  </h3>
				  <figure class="block__figure">
					<img src="{{ theme_url('img/icon2.png') }}" alt="" class="block__figure__image">
				  </figure>
				  <p class="paragraph">
					{{__('Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean  imperdiet. Etiam ultricies nisi vel augue.')}}
				  </p>
				</div>
			  </div>
			  <div class="col-lg-4">
				<div class="block" data-aos="fade-up">
				  <h3 class="title__block">
					{{__('Pay')}}
				  </h3>
				  <figure class="block__figure">
					<img src="{{ theme_url('img/icon3.png') }}" alt="" class="block__figure__image">
				  </figure>
				  <p class="paragraph">
					{{__('Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean  imperdiet. Etiam ultricies nisi vel augue.')}}
				  </p>
				</div>
			  </div>
			</div>
			  <div class="dec">
				<figure class="decoration">
				  <img src="{{ theme_url('img/decoration-two.png') }}" alt="">
				</figure>
			  </div>
		  </div>
		</section>

		<section class="section section--content">
		  <div class="container">
			<div class="row">
			  <div class="col-lg-6">
				<div class="content" data-aos="fade-right">
				  <h3 class="title__section">
					{{__('What does it offer?')}}
				  </h3>
				  <p class="paragraph">
					{{__('Cras id dui. Aenean ut eros et nisl sagittis vestibulum. Nullam nulla eros, ultricies sit amet, nonummy id, imperdiet feugiat, pede. Sed lectus. Donec mollis hendrerit risus. Phasellus nec sem in justo pellentesque facilisis.')}}
				  </p>
				</div>
			  </div>
			  <div class="col-lg-6">
				<table data-aos="fade-left">
				  <tr>
					<th>
					  <h4>{{__('2000+')}} <span>{{__('Link building totals')}}</span></h4>
					</th>
					<th>
					  <p>{{__('Vestibulum purus quam, scelerisque ut, mollis sed, nonummy id, metus.')}}</p>
					</th>
				  </tr>
				  <tr>
					<th>
					  <h4>{{__('1200+')}} <span>{{__('Crawled URLs / Day')}}</span></h4>
					</th>
					<th>
					  <p>{{__('Vestibulum purus quam, scelerisque ut, mollis sed, nonummy id, metus.')}}</p>
					</th>
				  </tr>
				  <tr>
					<th>
					  <h4>{{__('1500+')}} <span>{{__('Nam quam nunc luctus')}}</span></h4>
					</th>
					<th>
					  <p>{{__('Vestibulum purus quam, scelerisque ut, mollis sed, nonummy id, metus.')}}</p>
					</th>
				  </tr>
				</table>
			  </div>
			</div>
		  </div>
		  <figure class="decoration">
			<img src="{{ theme_url('img/decoration-three.png') }}" alt="">
		  </figure>
		</section>

		<section class="section section--content-blocks">
		  <div class="container">
			<div class="content-block">
			  <div class="row">
				<div class="col-xl-6 col-lg-5">
				  <figure data-aos="fade-right">
					<img src="{{ theme_url('img/image-1.jpg') }}" alt="">
				  </figure>
				</div>
				<div class="col-xl-6 col-lg-7">
				  <div class="content" data-aos="fade-left">
					<h3 class="title__section">
						{{__('What does it offer?')}}
					</h3>
					<p class="paragraph">
						{{__('Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem.')}}
					</p>
					<ul>
					  <li>
						<i class="fa-solid fa-check"></i> {{__('Vestibulum purus quam, scelerisque ut, mollis sed')}}
					  </li>
					  <li>
						<i class="fa-solid fa-check"></i> {{__('Vestibulum ante ipsum primis in faucibus orci luctus et ultrices')}}
					  </li>
					  <li>
						<i class="fa-solid fa-check"></i> {{__('Vestibulum purus quam, scelerisque ut, mollis sed')}}
					  </li>
					  <li>
						<i class="fa-solid fa-check"></i> {{__('Vestibulum ante ipsum primis in faucibus orci luctus et ultrices')}}
					  </li>
					</ul>
					<a href="#" class="btn btn-primary">{{__('Register')}}</a>
				  </div>
				</div>
			  </div>
			  <figure class="decoration">
				<img src="{{ theme_url('img/decoration-four.png') }}" alt="">
			  </figure>
			</div>
			<div class="content-block">
			  <div class="row">
				<div class="col-xl-6 col-lg-7">
				  <div class="content" data-aos="fade-right">
					<h3 class="title__section">
						{{__('Try it out now!')}}
					</h3>
					<p class="paragraph">
						{{__('Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem.')}}
					  <br><br>
					  <strong>
						{{__('Registreren op ons platform is helemaal gratis')}} <br> {{__('Aarzel dus niet om een kijkje te nemen!')}}
					  </strong>
					</p>
					<a href="{{ route('register') }}" class="btn btn-primary">
						{{__('Register now')}}
					  </a>
				  </div>
				</div>
				<div class="col-xl-6 col-lg-5">
				  <figure data-aos="fade-left">
					<img src="{{ theme_url('img/image-2.jpg')}}" alt="">
				  </figure>
				</div>
			  </div>
			  <figure class="decoration-two">
				<img src="{{ theme_url('img/decoration-four.png') }}" alt="">
			  </figure>
			</div>
		  </div>
		</section>

		<footer>
		  <button id="arrow"><i class="fa-regular fa-chevron-up"></i></button>
		  <div class="widgets">
			<div class="container">
			  <div class="row">
				<div class="col-lg-5">
				  <div class="widget">
					<figure>
					  <a href="#">
						<img src="{{ theme_url('img/logo.png') }}" alt="">
					  </a>
					</figure>
					<p class="paragraph">
						{{__('Vestibulum purus quam, scelerisque ut, mollis sed, nonummy id, metus. Nullam accumsan lorem in dui. Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia.')}}
					</p>
				  </div>
				</div>
				<div class="col-lg-2 ms-auto mr-auto">
				  <div class="widget">
					<ul>
					  <li>
						<a href="#">
							{{__('Dashboard')}}
						</a>
					  </li>
					  <li>
						<a href="#">
							{{__('My account')}}
						</a>
					  </li>
					  <li>
						<a href="#">
							{{__('Help Center')}}
						</a>
					  </li>
					  <li>
						<a href="#">
							{{__('Ask for support')}}
						</a>
					  </li>
					</ul>
				  </div>
				</div>
				<div class="col-lg-2 ms-auto mr-auto">
				  <div class="widget">
					<ul>
					  <li>
						<a href="#">
							{{__('Support')}}
						</a>
					  </li>
					  <li>
						<a href="#">
							{{__('Help Center')}}
						</a>
					  </li>
					  <li>
						<a href="#">
							{{__('Terms of use')}}
						</a>
					  </li>
					  <li>
						<a href="#">
							{{__('Privacy policy')}}
						</a>
					  </li>
					  <li>
						<a href="#">
							{{__('Cookies policy')}}
						</a>
					  </li>
					</ul>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		  <div class="bottom">
			<div class="container">
			  <span>{{__('Linkbuildings - All rights reserved Copyright © 2022')}}</span>
			</div>
		  </div>
		</footer>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo Theme::url('js/bootstrap.bundle.js');?>"></script>
		<script type="text/javascript" src="<?php echo Theme::url('js/aos.js');?>"></script>
		<script type="text/javascript" src="<?php echo Theme::url('js/app.js');?>"></script>
	  </body>
</html>
