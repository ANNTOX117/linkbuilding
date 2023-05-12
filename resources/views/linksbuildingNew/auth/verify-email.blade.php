<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{__('Registration')}} | {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="<?php echo Theme::url('css/bootstrap_home.min.css');?>">
    <link rel="stylesheet" href="<?php echo Theme::url('css/all.min.css');?>">
    <link rel="stylesheet" href="<?php echo Theme::url('css/aos.css');?>">
    <link rel="stylesheet" href="<?php echo Theme::url('css/style.css');?>">
</head>
<body>
    <section class="section section--logging-register">
    <figure class="decoration">
      <img src="{{ theme_url('img/decoration.png') }}" alt="">
    </figure>
    <figure class="decoration-two">
      <img src="{{ theme_url('img/decoration-three.png') }}" alt="">
    </figure>
    <figure class="figure">
      <img src="{{ theme_url('img/image-one.png') }}" alt="">
    </figure>
      <div class="container">
        <div class="col-lg-4 col-md-6 ms-auto">
          <div class="form">
            <figure class="logo">
              <a class="navbar-brand" href="#">
                <img src="{{ theme_url('img/logo.png') }}" alt="">
              </a>
            </figure>
            <h2 class="title__section mt-5">
            </h2>
            <h3 class="title__block text-center mt-5">
              <span>{{__('Verify your email')}}</span>
            </h3>
            @if(session('success'))
                <div class="alert alert-primary mx-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if(@$errors->any())
                <div class="alert alert-danger mx-4" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('status') == 'verification-link-sent')
                <div class="alert alert-primary mx-4" role="alert">
                    {{__('A new verification link has been sent to the email address you provided during registration.')}}
                </div>
            @endif

            <div class="alert alert-light mx-4" role="alert">
                <p>{{__('verify_thanks')}}</p>
            </div>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-verify" style="margin-top:30px; width:100%">{{__('Resend Verification Email')}}</button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="fadeIn fourth bg-white btn">{{__('Log Out')}}</button>
            </form>
          </div>
        </div>
      </div>
    </section>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo Theme::url('js/bootstrap.bundle.js');?>"></script>
    <script type="text/javascript" src="<?php echo Theme::url('js/aos.js');?>"></script>
    <script type="text/javascript" src="<?php echo Theme::url('js/app.js');?>"></script>
  </body>
</html>