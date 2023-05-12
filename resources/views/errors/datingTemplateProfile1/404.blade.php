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
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KG8CG88"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <header>
        <nav class="navbar navbar-expand-lg navbar">
          <div class="container">
            {{-- <a class="navbar-brand" href="/">
				<img src="{{ theme_url('img/logo.png') }}" alt="" style="width: 250px; height: auto; align-self: center;">
            </a> --}}
          </div>
        </nav>
    </header>
        <section class="section section--logging-register">
            <div class="container-scroller">
                <div class="row justify-content-center align-content-center" style="height: 60vh;">
                    <h1 style="font-size: 65px; color: #2c3e50; font-weight: 900; font-family: 'Figtree',sans-serif;">404 Not Found</h1>
                </div>
            </div>
            <div class="text-center">
                <a href="{{route("home")}}" class="btn btn-primary p-3">{{__("Return Home")}}</a>
            </div>
        </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="<?php echo Theme::url('js/popper.min.js'); ?>"></script>
    <script src="<?php echo Theme::url('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo Theme::url('js/pages.min.js'); ?>"></script>
    <script src="<?php echo Theme::url('js/masonry.min.js'); ?>"></script>
</body>
</html>
