<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{__('Registration')}} | {{ config('app.name', 'Laravel') }}</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="<?php echo Theme::url('css/auth.css'); ?>">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <div id="formContent">
            <div class="first py-4">
                <div class="d-flex align-items-center justify-content-center">
                    <img src="{{ theme_url('images/logo.svg') }}" id="icon" alt="User Icon"/>
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
            <div class="mb-4 mx-3 text-sm text-gray-600">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </div>
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div>
                    {{-- <x-jet-label for="password" value="{{ __('Password') }}" />
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus /> --}}

                    <input type="password" id="password" class="fadeIn second" name="password" autocomplete="current-password" placeholder="{{__('Password')}}">
                </div>
                <div class="flex justify-end mt-4">
                    {{-- <x-jet-button class="ml-4">
                        {{ __('Confirm') }}
                    </x-jet-button> --}}

                    <input type="submit" class="fadeIn fourth px-5 mx-0" value="{{__('Confirm')}}">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
