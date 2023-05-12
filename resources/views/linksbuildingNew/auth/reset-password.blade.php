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
    <link rel="stylesheet" href="<?=phpb_theme_asset('frontend/css/addons.css'); ?>">
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
              <span>{{__('Create new password')}}</span>
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
            <form method="POST" action="{{ route('password.update') }}">
              <input type="hidden" name="token" value="{{ $request->route('token') }}">
              @csrf
                <label>{{__('Email')}}</label>
              
              <input type="text" id="name" class="form-control" name="email" value="{{old('email')}}">
              <label>{{__('Password')}}</label>
              
              <input type="text" id="name" class="form-control" name="password" autocomplete="new-password">
              <label>{{__('Password confirmation')}}</label>
              
              <input type="text" id="name" class="form-control" name="password_confirmation" autocomplete="new-password">
              <button type="submit" class="btn btn-primary btn-reset" style="margin-top:30px; width:auto;">
                {{__('Reset Password')}}
                <i class="fa">
                  <div class="fakeMasonry-loader d-none" onMouseOver="this.style.color='#B53471'" onMouseOut="this.style.color='#FFF'">
                    <div class="loader" style="animation: rotate 2s linear infinite; margin-left: 10px; font-size: 20px; line-height: 1; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-spinner"></i>
                    </div>
                  </div>
                </i>
              </button>
            </form>
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
      $(document).on('click', '.btn-reset', function() {
        $('.btn-reset').addClass('disabled');
        var email    = $('input[id="login"]').val();
        // var password = $('input[id="password"]').val();

        $('.btn-reset').addClass('disabled');
        $('.fakeMasonry-loader').removeClass('d-none');
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#_token').val()
            }
        });
        $.ajax({
            type: 'post',
            url: '/reset-password',
            data: {
                email: email
            },
            dataType: 'json',
            timeout: 10000,
            beforeSend: function() {
                $('.btn-reset').addClass('disabled');
                $('.fakeMasonry-loader').removeClass('d-none');
            },
            success: function(response){
                window.location.href = request.responseText;
                if(response.login) {
                    window.location.href = response.url;
                    $('#error_show').text('');
                    $('#login_error').addClass('d-none');
                }
                $('.btn-reset').removeClass('disabled');
                $('.fakeMasonry-loader').addClass('d-none');
            },
            error: function(request,status,errorThrown) {
                $('.btn-reset').removeClass('disabled');
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
                $('.btn-reset').show();
            }
        });
    });
    </script>
</html>