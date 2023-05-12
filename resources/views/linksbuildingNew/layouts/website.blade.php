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
	<title>{{ $title }} | {{ $page }}</title>
    @if(isset($meta_info) && !empty($meta_info))
        <meta name="title" content="{{ $meta_info->meta_title }}">
    @elseif(isset($meta_title) and !empty($meta_title)) 
        <meta name="title" content="{{ $meta_title }}">
    @endif
    @if(isset($meta_info) && !empty($meta_info))
        <meta name="description" content="{{ $meta_info->meta_description }}">
    @elseif(isset($meta_description) and !empty($meta_description))
        <meta name="description" content="{{ $meta_description }}">
    @endif
    @if(isset($meta_info) && !empty($meta_info))
        <meta name="keywords" content="{{ $meta_info->meta_keywords }}">
    @elseif(isset($meta_keywords) and !empty($meta_keywords))
        <meta name="keywords" content="{{ $meta_keywords }}">
    @endif
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
        .description-links{ font-family: "roboto",sans-serif !important; font-weight: normal; font-size: 15px; line-height: 1.3; color:#6c757d;}
        .description-a{ font-family: "roboto",sans-serif !important; font-weight: normal; line-height: 1.3; font-size: 14px;}
        .grid h3, .masonry h3 { text-transform: capitalize; }
        .minh-105{ min-height: 105vh; }
        /* #fit-width { display: flex; -webkit-justify-content: center; justify-content: center; -webkit-align-items: center; align-items: center; } */
        .carousel-item{ min-height: 100px; text-align: center;}
        .card-titles { font-size: 1.1rem; font-weight: 400; }
        .post-category { cursor: pointer; }
        .post-category-a { display: inline; }
        .post-category:hover { color: #313334 }
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }
        .btn.btn-primary{
          background: {{ $site->box ?? '#0064fb' }};
          border-color: {{ $site->box ?? '#0064fb' }};
        }
        .carousel-control-next {
            color: {{ $site->box ?? '#0064fb' }};
            background: white;
            border-top-left-radius: 50px;
            border-bottom-left-radius: 50px;
            height: 100px;
            width: 50px;
            margin: auto;
            transition: .3s ease-out;
            opacity: 0.9;
        }
        .carousel-control-prev {
            color: {{ $site->box ?? '#0064fb' }};
            background: white;
            border-top-right-radius: 50px;
            border-bottom-right-radius: 50px;
            height: 100px;
            width: 50px;
            margin: auto;
            transition: .3s ease-out;
            opacity: 0.9;
        }
        .carousel-control-next:hover, .carousel-control-prev:hover {
            color: #fff !important;
            background: {{ $site->box ?? '#0064fb' }} !important;
        }
        .carousel-control-next:focus, .carousel-control-prev:focus {
            color: #fff !important;
            background: {{ $site->box ?? '#0064fb' }} !important;
        }
        .carousel-item.active{
            opacity: 1;
            transform: none
        }
        .carousel-item{
            opacity: 0;
            transform: translateX(-50px);
        }
        .footer.color-box.mt-5 a{
            /* color: {{ color_inverse($site->box) }} !important; */
            /* color: rgb({{ rgb_best_contrast(hexdec(str_split(trim($site->box, '#'), 2)[0]), hexdec(str_split(trim($site->box, '#'), 2)[1]), hexdec(str_split(trim($site->box, '#'), 2)[2])) }}) !important; */
            color: {{ rgb_best_contrast(hexdec(str_split(trim($site->box, '#'), 2)[0]), hexdec(str_split(trim($site->box, '#'), 2)[1]), hexdec(str_split(trim($site->box, '#'), 2)[2])) }} !important;
            text-decoration: underline;
        }
        .footer .container ul li a{
            color: black !important;
            text-decoration: underline;
        }
        @media (max-width: 991.98px){
            .navbar-expand-lg > .container, .navbar-expand-lg > .container-fluid, .navbar-expand-lg > .container-sm, .navbar-expand-lg > .container-md, .navbar-expand-lg > .container-lg, .navbar-expand-lg > .container-xl {
            padding-left: 3%;
            }
        }
    </style>
    <link href="<?php echo Theme::url('css/addons.css'); ?>" rel="stylesheet">
	@stack('styles')
	@livewireStyles
</head>
<body>
    @php
        $domain  = domain();
        $category = subdomain();
    @endphp
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KG8CG88"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
	<div id="app">
		<div class="top-bar mb-3">
                    <nav id="navbar-page-header" class="navbar navbar-expand-lg color-menu shadow justify-content-between">
                        <div class="container-fluid">
                        <a href="/" class="navbar-brand text-uppercase font-weight-bold d-flex align-items-center adjust-text">
                            @if (!empty($site->logo))
                                <img class="mr-1" height="25" src="{{ asset($site->logo) }}">
                            @endif
                            {{ $site->name }}
                        </a>
                        <button class="navbar-toggler adjust-text mr-2" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarText">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a id="norm" href="/" class="nav-link h5 mb-0 color-menu adjust-text @if($section == __('home')) active @endif">{{ __('Home')}}</a>
                                </li>
                                @if ($site->type != 'Blog page')
                                    <li class="nav-item">
                                        <a href="{{ route('blogs')}}" class="nav-link h5 mb-0 color-menu adjust-text @if($section == 'blog') active @endif">{{ __('Blog')}}</a>
                                    </li>
                                @endif
                                {{-- @if(empty(session('category'))) --}}
                                @if(empty($category))
                                <li class="nav-item">
                                    <a href="{{ route('subpages')}}" class="nav-link h5 mb-0 color-menu adjust-text @if($section == 'daughters') active @endif">{{ __('Categories')}}</a>
                                </li>
                                @endif
                                <li class="nav-item">

                                    <a href="{{ route('contact') }}" class="nav-link h5 mb-0 color-menu adjust-text @if($section == 'contact') active @endif">{{ __('Contact')}} </a>

                                </li>
                            </ul>
                        </div>
                    </div>
                    </nav>
                </div>
                @if(count(Request::segments()) > 0)
                <div class="container-fluid">
                    <div class="row">
                            <div class="col-md-10 col-sm-12 mx-auto">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb pl-0">
                                    @if(count(Request::segments()) > 0)
                                        <li class="breadcrumb-item"><a class="color-links adjust-text"  href="/">HOME</a></li>
                                    @endif
                                @foreach(Request::segments() as $segment)
                                    @if(!is_numeric($segment))
                                        @if ($segment == 'blog' || ($segment == 'blogs' && count(Request::segments()) > 1))
                                            <li class="breadcrumb-item"><a class="color-links adjust-text" href="{{ route('blogs') }}">{{ strtoupper($segment)}}</a></li>
                                        @else
                                            @if(count(Request::segments()) > 2 && $segment == Request::segment(2) && !is_numeric(Request::segment(3)))
                                                <li class="breadcrumb-item"><a class="color-links adjust-text" href="{{ route('blog', ['category' => $segment, 'id' => $id_category]) }}">{{ strtoupper($segment)}}</a></li>
                                        @else
                                            <li class="breadcrumb-item"><a class="color-links adjust-text">{{ strtoupper($segment)}}</a></li>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                @endif
                <div class="container-scroller minh-105">
                {{ $slot }}

            
		        </div>

                @if(!empty($footer) or !empty($footer2) or !empty($footer3) or !empty($footer4))
                <footer class="footer color-box @if(!empty($footer) or !empty($footer2) or !empty($footer3) or !empty($footer4)) mt-5 @endif">
                    @if(!empty($footer) or !empty($footer2) or !empty($footer3) or !empty($footer4))
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3 my-3">@if(!empty($footer)) {!! $footer !!} @endif</div>
                                <div class="col-md-3 my-3">@if(!empty($footer2)) {!! $footer2 !!} @endif</div>
                                <div class="col-md-3 my-3">@if(!empty($footer3)) {!! $footer3 !!} @endif</div>
                                <div class="col-md-3 my-3">@if(!empty($footer4)) {!! $footer4 !!} @endif</div>
                            </div>
                        </div>
                    @endif
                </footer>
            @endif
            <div class="copyright color-box text-center @if(empty($footer) AND empty($footer2) AND empty($footer3) AND empty($footer4)) fixed-bottom @endif">
                {{-- <p class="mb-0 adjust-text">&copy; {{__('Copyright')}} {{date('Y')}}, {{__('All rights reserved')}}&nbsp;&nbsp;&nbsp; @if(!empty(session('category')))Powered by <a href="{{ $site->url }}">{{ $site->name }}</a>@endif</p> --}}
                <p class="mb-0 adjust-text">&copy; {{__('Copyright')}} {{date('Y')}}, {{__('All rights reserved')}}&nbsp;&nbsp;&nbsp; @if(!empty($category))Powered by <a class="adjust-text" href="{{ $site->url }}">{{ $site->name }}</a>@endif</p>
            </div>
	</div>
    @unless(Cookie::get('cookies'))
        <div class="cookies-wrapper">
            <div class="cookies-container">
                <p>{{__('cookies_message')}} <a href="{{ route('privacy')}}">{{__('More information')}}</a></p>
                <button class="cancel" type="button" onclick="window.location.href='{{ get_root() }}'">{{__('Cancel')}}</button>
                <button class="agree" type="button">{{__('Accept')}}</button>
            </div>
        </div>
    @endunless
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="<?php echo Theme::url('js/popper.min.js'); ?>"></script>
    <script src="<?php echo Theme::url('js/bootstrap.min.js'); ?>"></script>
    {{-- <script src="<?php echo Theme::url('js/pages.min.js'); ?>"></script> --}}
    <script src="<?php echo Theme::url('js/pages.js'); ?>"></script>
    <script src="<?php echo Theme::url('js/masonry.min.js'); ?>"></script>
    @stack('scripts')
    @livewireScripts
</body>
</html>
