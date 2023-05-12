<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{__('Please confirm your email')}} | {{ config('app.name', 'Laravel') }}</title>
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

        @if(session('status') == 'verification-link-sent')
            <div class="alert alert-primary mx-4" role="alert">
                {{__('A new verification link has been sent to the email address you provided during registration.')}}
            </div>
        @endif

        <div class="alert alert-light mx-4" role="alert">
            <p>{{__('verify_thanks')}}</p>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div>
                    <input type="submit" class="fadeIn fourth btn-verify" value="{{__('Resend Verification Email')}}">
                </div>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <input type="submit" class="fadeIn fourth bg-white" value="{{__('Log Out')}}">
            </form>
        </div>
    </div>
</div>
</body>
</html>
