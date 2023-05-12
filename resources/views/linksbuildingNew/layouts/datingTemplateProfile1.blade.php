<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(isset($meta_title) && !empty($meta_title))
    <title>{{ $meta_title }} | {{ $page }}</title>
    @endif
    @if(isset($meta_description) && !empty($meta_description))
<meta name="description" content="{{ $meta_description}}">
    @endif
    @if(isset($meta_keyword) && !empty($meta_keyword))
<meta name="keywords" content="{{$meta_keyword}}">
    @endif
    @if(isset($site->favicon) && !empty($site->favicon))
<link rel="shortcut icon" href="{{$site->favicon}}" type="image/x-icon">
    @endif
    @if(isset($site->no_index_follow) && $site->no_index_follow)
<meta name="robots" content="noindex,nofollow">
    @endif
        <link href="<?php echo Theme::url('build/datingTemplateProfile1/assets/css/main.min.css'); ?>" rel="stylesheet">
        @stack('styles')
        @livewireStyles 
    </head>
    <body>
        <livewire:dating-template-profile1.templates.menu :site="$site"/>
        <div class="container-scroller minh-105">
            {{ $slot }}
        </div>
        {{-- @isset($extraSettings->google_analytics_code)
            {!! $extraSettings->google_analytics_code !!}
        @endisset --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>        
        <script src="<?php echo Theme::url('build/datingTemplateProfile1/assets/js/merge.min.js'); ?>"></script>
        @stack('scripts')
        @livewireScripts
    </body>
</html>