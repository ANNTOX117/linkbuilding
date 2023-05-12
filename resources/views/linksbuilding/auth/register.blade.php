<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{__('Registration')}} | {{ config('app.name', 'Laravel') }}</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<div class="wrapper">
    <div id="formContent">
        <div class="first py-4">
            <div class="d-flex align-items-center justify-content-center">
                <img src="{{ asset('images/logo.svg') }}" id="icon" alt="User Icon"/>
                <h4 class="m-0 pl-2">{{ env('APP_NAME') }}</h4>
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
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="text" id="name" class="fadeIn second" name="name" value="{{old('name')}}" placeholder="{{__('Name')}}">
            <input type="text" id="lastname" class="fadeIn second" name="lastname" value="{{old('lastname')}}"  placeholder="{{__('Lastname')}}">
            <input type="email" id="login" class="fadeIn second" name="email" value="{{old('email')}}" placeholder="{{__('Email')}}">
            <input type="password" id="password" class="fadeIn second" name="password" autocomplete="current-password" placeholder="{{__('Password')}}">
            <input type="password" id="password_confirmation" class="fadeIn second" name="password_confirmation" placeholder="{{__('Password Confirmation')}}">
            <select class="fadeIn second" name="country" placeholder="{{__('Country')}}">
                <option value="" selected>{{ __('Country')}}</option>
                @if(!empty($countries))
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" @if($country->id == 1) selected="selected" @endif>{{ $country->name }}</option>
                    @endforeach
                @endif
            </select>
            <input type="submit" class="fadeIn fourth" value="{{__('Register')}}">
        </form>
        <div id="formFooter">
            <a href="{{ route('login') }}">{{ __('Already registered?') }}</a>
        </div>
    </div>
</div>
</body>
</html>
