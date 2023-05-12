<section class="section section--content-blocks">
	<div class="container">
	<div class="content-block">
		<div class="row">
		<div class="col-xl-6 col-lg-5">
			<figure data-aos="fade-right">
				<img src="<?=phpb_theme_asset('img/image-1.jpg');?>" alt="">
			</figure>
		</div>
		<div class="col-xl-6 col-lg-7">
			<div class="content" data-aos="fade-left">
				<!-- [block slug="h3-title-section"] -->
				<h3 class="title__section">
					<?php echo __($block->setting('title_section_1'));?>
				</h3>
				[block slug="paragraph"]
				<!-- <p class="paragraph">
					<?=$block->setting('paragraph_1')?>
				</p> -->
				<ul>
					<!-- [block slug="li-icon-text"]
					[block slug="li-icon-text"]
					[block slug="li-icon-text"]
					[block slug="li-icon-text"] -->
					<li>
						<i class="fa-solid fa-check"></i> <?php echo __($block->setting('li_1'));?>
					</li>
					<li>
						<i class="fa-solid fa-check"></i> <?php echo __($block->setting('li_2'));?>
					</li>
					<li>
						<i class="fa-solid fa-check"></i> <?php echo __($block->setting('li_3'));?>
					</li>
					<li>
						<i class="fa-solid fa-check"></i> <?php echo __($block->setting('li_4'));?>
					</li>
				</ul>
				[block slug="register-button"]
			<!-- <a href="#" class="btn btn-primary">Register</a> -->
			</div>
		</div>
		</div>
		<figure class="decoration">
			<img src="<?=phpb_theme_asset('img/decoration-four.png');?>" alt="">
		</figure>
	</div>
	<figure class="decoration" style="z-index: -1 !important; opacity: 0.5; float:left; position: absolute; left: 0;">
		<img src="<?=phpb_theme_asset('img/decoration.png');?>" alt="">
	</figure>
	<div class="content-block">
		<div class="row">
			<div class="col-xl-6 col-lg-7">
				<div class="content" data-aos="fade-right">
				<!-- [block slug="h3-title"] -->
				<h3 class="title__section">
					<?php echo __($block->setting('title_section_2'));?>
				</h3>
				[block slug="paragraph"]
				[block slug="link-button"]
				<!-- <a href="{{ route('register') }}" class="btn btn-primary">
					Register now
					</a> -->
				</div>
			</div>
			<div class="col-xl-6 col-lg-5">
				<figure data-aos="fade-left">
					<img src="<?=phpb_theme_asset('img/image-2.jpg');?>" alt="">
				</figure>
			</div>
		</div>
		<figure class="decoration-two">
			<img src="<?=phpb_theme_asset('img/decoration-four.png');?>" alt="">
		</figure>
	</div>
	</div>
</section>