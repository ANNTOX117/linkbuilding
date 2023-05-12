<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ $title }} | {{ $page }}</title>
    @if(isset($meta_title) and !empty($meta_title)) <meta name="title" content="{{ $meta_title }}"> @endif
    @if(isset($meta_description) and !empty($meta_description)) <meta name="description" content="{{ $meta_description }}"> @endif
    @if(isset($meta_keywords) and !empty($meta_keywords)) <meta name="keywords" content="{{ $meta_keywords }}"> @endif
	<link href="https://fonts.gstatic.com" rel="preconnect">
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="<?php echo Theme::url('css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo Theme::url('css/pages.min.css'); ?>" rel="stylesheet">
    <style>
        .cheight{ min-height: 70vh; }
        .color-box { background-color: {{ $site->box ?? '#0064fb' }} !important; }
        .color-links { color: {{ $site->links ?? '#595959' }} !important; }
        .color-menu { background-color: {{ $site->menu ?? '#ffffff' }} !important; }
    </style>
	@stack('styles')
	@livewireStyles
</head>
<body>
	<div id="app">
		<div class="container-scroller">
            <div class="row justify-content-center">
                <div class="col-10 my-3 ">
                    <nav class="navbar navbar-expand-lg navbar-light color-menu shadow justify-content-between">
                        <a href="{{ route('homepage') }}" class="navbar-brand text-uppercase font-weight-bold d-flex align-items-center adjust-text">
                            @if (!empty($site->logo))
                                <img class="mr-1" height="25" src="{{ asset($site->logo) }}">
                            @endif
                            {{ $site->name }}
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarText">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a href="{{ route('homepage') }}" class="nav-link h5 mb-0 color-menu adjust-text @if($section == 'home') active @endif">{{ __('Home')}}</a>
                                </li>
                                <li class="nav-item">
                                 
                                    <a href="{{ route('blog_' . App::getLocale()) }}" class="nav-link h5 mb-0 color-menu adjust-text @if($section == 'blog') active @endif">{{ __('Blog')}}</a>
                                  
                                </li>
                                <?php /* ?>
                                @if(empty(session('category')))
                                <li class="nav-item">
                                    <a href="{{ route('daughters_' . App::getLocale()) }}" class="nav-link h5 mb-0 color-menu adjust-text @if($section == 'daughters') active @endif">{{ __('Daughters')}}</a>
                                </li>
                                @endif
                                <?php */ ?>
                                <li class="nav-item">
                                   
                                    <a href="{{ route('contact_' . App::getLocale()) }}" class="nav-link h5 mb-0 color-menu adjust-text @if($section == 'contact') active @endif">{{ __('Contact')}}</a>
                                
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                {{ $slot }}
            </div>

            @if(!empty($footer) or !empty($footer2) or !empty($footer3))
                <footer class="footer color-box fixed-bottom @if(!empty($footer) or !empty($footer2) or !empty($footer3)) mt-5 @endif">
                    @if(!empty($footer) or !empty($footer2) or !empty($footer3))
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 my-3 adjust-text">@if(!empty($footer)) {!! $footer !!} @endif</div>
                                <div class="col-md-3 my-3 adjust-text">@if(!empty($footer2)) {!! $footer2 !!} @endif</div>
                                <div class="col-md-3 my-3 adjust-text">@if(!empty($footer3)) {!! $footer3 !!} @endif</div>
                            </div>
                        </div>
                    @endif
                </footer>
            @endif
            <div class="copyright color-box text-center fixed-bottom @if(empty($footer) AND empty($footer2) AND empty($footer3)) fixed-bottom @endif">
                <p class="mb-0 adjust-text">&copy; {{__('Copyright')}} {{date('Y')}}, {{__('All rights reserved')}}</p>
            </div>
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
