<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{__('Login')}} | {{ config('app.name', 'Laravel') }}</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="<?php echo Theme::url('css/auth.css'); ?>">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="wrapper">
    <div id="formContent">
        <div class="first py-4">
            <div class="d-flex align-items-center justify-content-center">
                <img src="{{ theme_url('images/logo.svg') }}" id="icon" alt="User Icon"/>
                <h4 class="m-0 pl-2">{{ env('APP_NAME')  }}</h4>
            </div>
        </div>
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
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" id="login" class="fadeIn second" name="email" value="{{old('email')}}" placeholder="{{__('Email')}}">
            <input type="password" id="password" class="fadeIn third" name="password" autocomplete="current-password" placeholder="{{__('Password')}}">
            @if (Route::has('password.request'))
                <a class="w-100 my-3" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <input type="submit" class="fadeIn fourth" value="Log In">
        </form>
        <div id="formFooter">
            <a href="{{ route('register') }}">{{__('Register')}}</a>
        </div>
    </div>
</div>
</body>
</html>
