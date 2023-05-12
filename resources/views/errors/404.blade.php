<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KG8CG88');</script>
    <!-- End Google Tag Manager -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ !empty(session('website')) ? session('website')->title : 'Link building system' }} </title>
	<link href="https://fonts.gstatic.com" rel="preconnect">
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="<?php echo Theme::url('css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo Theme::url('css/pages.min.css'); ?>" rel="stylesheet">
    <style>
        .cheight{ min-height: 70vh; }
        .color-box { background-color: {{ session('website')->box ?? '#0064fb' }} !important; }
        .color-links { color: {{ session('website')->links ?? '#595959' }} !important; }
        .color-menu { background-color: {{ session('website')->menu ?? '#ffffff' }} !important; }
        .description-links{ font-family: "roboto",sans-serif !important; font-weight: normal; font-size: 15px; line-height: 1.3; color:#6c757d;}
        .description-a{ font-family: "roboto",sans-serif !important; font-weight: normal; line-height: 1.3; font-size: 14px;}
        .grid h3, .masonry h3 { text-transform: capitalize; }
        .minh-105{ min-height: 105vh; }
        #fit-width .js-masonry { margin: 0 auto; }
        .carousel-item{ min-height: 100px; text-align: center;}
        .card-titles{ font-size: 1.1rem; font-weight: 400; }
        .btn.btn-primary{
          background: {{ session('website')->box ?? '#0064fb' }};
          border-color: {{ session('website')->box ?? '#0064fb' }};
        }
    </style>
    <link href="<?php echo Theme::url('css/addons.css'); ?>" rel="stylesheet">
	@stack('styles')
	@livewireStyles
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KG8CG88"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @if (!empty(session('website')))
	<div id="app">
		<div class="top-bar mb-3">
                    <nav id="navbar-page-header" class="navbar navbar-expand-lg color-menu shadow justify-content-between">
                        <div class="container-fluid">
                        <a href="{{ route('homepage') }}" class="navbar-brand text-uppercase font-weight-bold d-flex align-items-center adjust-text">
                            @if (!empty(session('website')->logo))
                                <img class="mr-1" height="25" src="{{ asset(session('website')->logo) }}">
                            @endif
                            {{ session('website')->name }}
                        </a>
                        <button class="navbar-toggler adjust-text" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarText">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a href="{{ route('homepage') }}" class="nav-link h5 mb-0 color-menu adjust-text">{{ __('Home')}}</a>
                                </li>
                                @if (session('website')->type != 'Blog page')
                                    <li class="nav-item">
                                        <a href="{{ route('blog_' . App::getLocale()) }}" class="nav-link h5 mb-0 color-menu adjust-text">{{ __('Blog')}}</a>
                                    </li>
                                @endif
                                @if(empty(session('category')))
                                <li class="nav-item">
                                    <a href="{{ route('subpages_' . App::getLocale()) }}" class="nav-link h5 mb-0 color-menu adjust-text">{{ __('Categories')}}</a>
                                </li>
                                @endif
                                <li class="nav-item">

                                    <a href="{{ route('contact_' . App::getLocale()) }}" class="nav-link h5 mb-0 color-menu adjust-text">{{ __('Contact')}}</a>

                                </li>
                            </ul>
                        </div>
                    </div>
                    </nav>
                </div>
                <div class="container-scroller">
                    <div class="row justify-content-center align-content-center" style="height: 80vh;">
                        <h1 style="font-size: 65px;">404 Not Found</h1>
                    </div>
		        </div>
                @if(!empty(session('website')->footer) or !empty(session('website')->footer2) or !empty(session('website')->footer3) or !empty(session('website')->footer4))
                <footer class="footer color-box @if(!empty(session('website')->footer) or !empty(session('website')->footer2) or !empty(session('website')->footer3) or !empty(session('website')->footer4)) mt-5 @endif">
                    @if(!empty(session('website')->footer) or !empty(session('website')->footer2) or !empty(session('website')->footer3) or !empty(session('website')->footer4))
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3 my-3 adjust-text">@if(!empty(session('website')->footer)) {!! session('website')->footer !!} @endif</div>
                                <div class="col-md-3 my-3 adjust-text">@if(!empty(session('website')->footer2)) {!! session('website')->footer2 !!} @endif</div>
                                <div class="col-md-3 my-3 adjust-text">@if(!empty(session('website')->footer3)) {!! session('website')->footer3 !!} @endif</div>
                                <div class="col-md-3 my-3 adjust-text">@if(!empty(session('website')->footer4)) {!! session('website')->footer4 !!} @endif</div>
                            </div>
                        </div>
                    @endif
                </footer>
            @endif
            <div class="copyright color-box text-center @if(empty(session('website')->footer) AND empty(session('website')->footer2) AND empty(session('website')->footer3) AND empty(session('website')->footer4)) fixed-bottom @endif">
                <p class="mb-0 adjust-text">&copy; {{__('Copyright')}} {{date('Y')}}, {{__('All rights reserved')}}&nbsp;&nbsp;&nbsp; @if(!empty(session('category')))Powered by <a href="{{ route('homepage') }}">{{ session('website')->name }}</a>@endif</p>
            </div>
	</div>
    @unless(Cookie::get('cookies'))
        <div class="cookies-wrapper">
            <div class="cookies-container">
                <p>{{__('cookies_message')}} <a href="{{ route('privacy_' . App::getLocale()) }}">{{__('More information')}}</a></p>
                <button class="cancel" type="button" onclick="window.location.href='{{ get_root() }}'">{{__('Cancel')}}</button>
                <button class="agree" type="button">{{__('Accept')}}</button>
            </div>
        </div>
    @endunless
    @else
    <header>
        <nav class="navbar navbar-expand-lg navbar">
          <div class="container">
            <a class="navbar-brand" href="/">
				<img src="{{ theme_url('img/logo.png') }}" alt="" style="width: 250px; height: auto; align-self: center;">
            </a>
          </div>
        </nav>
    </header>
        <section class="section section--logging-register">
            <div class="container-scroller">
                <div class="row justify-content-center align-content-center" style="height: 80vh;">
                    <h1 style="font-size: 65px; color: #2c3e50; font-weight: 900; font-family: "Figtree",sans-serif;">404 Not Found</h1>
                </div>
            </div>
            <figure class="decoration" style="position: absolute; left:0; top:0;">
            <img src="{{ theme_url('img/decoration.png') }}" alt="">
            </figure>
            <figure class="decoration-two" style="position: absolute; right:0; top:0;">
            <img src="{{ theme_url('img/decoration-three.png')}}" alt="">
            </figure>
        </section>
    @endif
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="<?php echo Theme::url('js/popper.min.js'); ?>"></script>
    <script src="<?php echo Theme::url('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo Theme::url('js/pages.min.js'); ?>"></script>
    <script src="<?php echo Theme::url('js/masonry.min.js'); ?>"></script>
    @stack('scripts')
    @livewireScripts
</body>
</html>
