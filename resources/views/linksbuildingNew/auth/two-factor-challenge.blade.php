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
<div class="wrapper bg-white">
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

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600 px-3" x-show="! recovery">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </div>

            <div class="mb-4 text-sm text-gray-600 px-3" x-show="recovery">
                {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
            </div>

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-jet-input id="code" class="fadeIn second" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" placeholder="{{__('Name')}}"/>
                </div>

                <div class="mt-4" x-show="recovery">
                    <x-jet-input id="recovery_code" class="fadeIn second" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" placeholder="{{__('Recovery Code')}}"/>
                </div>

                <div class="d-flex flex-column mt-2 px-3">
                    <input type="button" class="fadeIn fourth px-4 mx-0 mb-2"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    " value="{{ __('Use a recovery code') }}">

                    <input type="button" class="fadeIn fourth px-4 mx-0 mb-2"
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    " value="{{ __('Use an authentication code') }}">
                    {{-- <x-jet-button class="fadeIn fourth">
                        {{ __('Log in') }}
                    </x-jet-button> --}}

                    <input type="submit" class="fadeIn fourth px-4 mx-0 mb-2" value="{{__('Log in')}}">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
