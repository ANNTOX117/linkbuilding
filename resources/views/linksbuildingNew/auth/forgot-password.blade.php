<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{__('Login')}} | {{ config('app.name', 'Laravel') }}</title>
    <link href="<?=phpb_theme_asset('frontend/css/bootstrap.min.css');?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo Theme::url('css/all.min.css');?>">
    <link rel="stylesheet" href="<?php echo Theme::url('css/aos.css');?>">
    <link rel="stylesheet" href="<?php echo Theme::url('css/style.css');?>">
    <link rel="stylesheet" href="<?=phpb_theme_asset('frontend/css/addons.css'); ?>">
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
              <span>{{__('Forgot your')}}</span> <br> {{__('Password ?')}}
            </h2>
            <label class="m-3">
                No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one. <br>
            </label>
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

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
            <form class="m-5" method="POST" action="{{ route('password.email') }}">
                @csrf
              <label>{{__('Email')}}</label>
              
              <input type="text" id="login" class="form-control" name="email" value="{{old('email')}}">
              {{-- <label>{{__('Password')}}</label> --}}
              
              {{-- <input type="password" id="password" class="form-control" name="password" autocomplete="current-password">
              @if (Route::has('password.request'))
                <a class="recover-password d-block" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
              @endif --}}
              <button type="submit" class="btn btn-primary btn-forgot">
                {{__('Reset link')}}
                <i class="fa">
                  <div class="fakeMasonry-loader d-none" onMouseOver="this.style.color='#B53471'" onMouseOut="this.style.color='#FFF'">
                    <div class="loader" style="animation: rotate 2s linear infinite; margin-left: 10px; font-size: 20px; line-height: 1; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-spinner"></i>
                    </div>
                  </div>
                </i>
              </button>
            </form>
            {{-- <a href="{{ route('register') }}" class="create-accout d-block">{{__('Create Account')}}</a> --}}
          </div>
        </div>
      </div>
    </section>
  </body>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo Theme::url('js/bootstrap.bundle.js');?>"></script>
    <script type="text/javascript" src="<?php echo Theme::url('js/aos.js');?>"></script>
    <script type="text/javascript" src="<?php echo Theme::url('js/app.js');?>"></script>
    <script>
      $(document).on('click', '.btn-forgot', function() {
        $('.btn-forgot').addClass('disabled');
        var email    = $('input[id="login"]').val();
        // var password = $('input[id="password"]').val();

        $('.btn-forgot').addClass('disabled');
        $('.fakeMasonry-loader').removeClass('d-none');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#_token').val()
            }
        });
        $.ajax({
            type: 'post',
            url: '/forgot-password',
            data: {
                email: email
            },
            dataType: 'json',
            timeout: 10000,
            beforeSend: function() {
                $('.btn-forgot').addClass('disabled');
                $('.fakeMasonry-loader').removeClass('d-none');
            },
            success: function(response){
                window.location.href = request.responseText;
                if(response.login) {
                    window.location.href = response.url;
                    $('#error_show').text('');
                    $('#login_error').addClass('d-none');
                }
                $('.btn-forgot').removeClass('disabled');
                $('.fakeMasonry-loader').addClass('d-none');
            },
            error: function(request,status,errorThrown) {
                $('.btn-forgot').removeClass('disabled');
                $('.fakeMasonry-loader').addClass('d-none');
                $('#error_show').text('');
                $('#login_error').addClass('d-none');
                if(request.status != 200){
                    var fail = jQuery.parseJSON(request.responseText);
                    var line_errors = '';
                    $.each( fail.errors, function( key, value ) {
                        $.each(value, function( ite, val ) {
                            line_errors = line_errors+val+'<br>';
                        });
                    });
                    $('#error_show').html(line_errors);
                    $('#login_error').removeClass('d-none');
                } else {
                    window.location.href = request.responseText;
                }
            },
            complete: function() {
                $('.btn-forgot').show();
            }
        });
    });
    </script>
</html>
