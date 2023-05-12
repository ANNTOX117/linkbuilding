<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{__('Login')}} | {{ config('app.name', 'Laravel') }}</title>
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
      <img src="{{ theme_url('img/decoration-three.png')}}" alt="">
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
            <h2 class="title__section">
              <span>{{__('Hello')}}</span> <br> {{__('Good Morning')}}
            </h2>
            <h3 class="title__block text-center">
              <span>{{__('Login your account')}}</span>
            </h3>
            @if(session('success'))
                <div class="alert alert-primary mx-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if(@$errors->any())
                <div class="alert alert-danger mx-auto" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
              <label>{{__('Email')}}</label>

              <input type="text" id="login" class="form-control" name="email" value="{{old('email')}}">
              <label>{{__('Password')}}</label>

              <input type="password" id="password" class="form-control" name="password" autocomplete="current-password">
              @if (Route::has('password.request'))
                <a class="recover-password d-block" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
              @endif
              <button type="submit" class="btn btn-primary">{{__('Login')}}</button>
            </form>
            <a href="{{ route('register') }}" class="create-accout d-block">{{__('Create Account')}}</a>
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
