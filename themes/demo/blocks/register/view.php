<section class="section section--logging-register">
    <figure class="decoration">
      <img src="<?=phpb_theme_asset('img/decoration.png');?>" alt="">
    </figure>
    <figure class="decoration-two">
      <img src="<?=phpb_theme_asset('img/decoration-three.png');?>" alt="">
    </figure>
    <figure class="figure">
      <img src="<?=phpb_theme_asset('img/image-one.png');?>" alt="">
    </figure>
      <div class="container">
        <div class="col-lg-4 col-md-6 ms-auto">
          <div class="form">
            <figure class="logo">
              <a class="navbar-brand" href="#">
                <img src="<?=phpb_theme_asset('img/logo.png');?>" alt="">
              </a>
            </figure>
            <h2 class="title__section">
            </h2>
            <h3 class="title__block text-center">
              <span>
                <?php echo __($block->setting('create_text'));?>
              </span>
            </h3>
            <div class="alert alert-danger mx-auto w-100 d-none" role="alert" id="register_error">
              <p class="mb-0" id="error_show"></p>
            </div>
            <!-- <form method="POST" action=""> -->
              <input id="_token" type="hidden" value="<?=csrf_token()?>">
              <label><?php echo __('Name');?></label>

              <input type="text" id="name" class="form-control" name="name" value="<?=old('name')?>">
              <label><?php echo __('Lastname');?></label>

              <input type="text" id="lastname" class="form-control" name="lastname" value="<?=old('lastname')?>">
              <label><?php echo __('Email');?></label>

              <input type="email" id="login" class="form-control" name="email" value="<?=old('email')?>">
              <label><?php echo __('Password');?></label>

              <input type="password" id="password" class="form-control" name="password" autocomplete="current-password">
              <label><?php echo __('Password confirmation');?></label>

              <input type="password" id="password_confirmation" class="form-control" name="password_confirmation">
              <button  class="btn btn-primary btn-register" style="width:auto; margin-top:30px">
                <?php echo __($block->setting('create_account'));?>
                <i class="fa">
                  <div class="fakeMasonry-loader d-none" onMouseOver="this.style.color='#B53471'" onMouseOut="this.style.color='#FFF'">
                    <div class="loader" style="animation: rotate 2s linear infinite; margin-left: 10px; font-size: 20px; line-height: 1; display: flex; align-items: center; justify-content: center;">
                      <i class="fas fa-spinner"></i>
                    </div>
                  </div>
                  </i>
              </button>
            <!-- </form> -->
            <a href="<?php echo LaravelLocalization::localizeUrl('/login') ?>" class="create-accout d-block">
              <?php echo __($block->setting('already_text'));?>
            </a>
          </div>
        </div>
      </div>
    </section>
