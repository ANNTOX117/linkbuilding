<section id="section-one" class="section section--hero">
<style>

</style>
	<figure class="figure">
	<img src="<?=phpb_theme_asset('img/image-one.png');?>" alt="">
	</figure>
	<figure class="decoration">
	<img src="<?=phpb_theme_asset('img/decoration.png');?>" alt="">
	</figure>
	<div class="container">
		
	<div class="row">
		<div class="col-lg-6">
		<div class="content" data-aos="fade-right">
			[block slug="h1-title-main"]
			<!-- <h1 class="title__main">
			The most complete <br>
			backlink path
			</h1> -->
			[block slug="paragraph"]
			<!-- <p class="paragraph">
			Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut.
			</p> -->
			<div class="buttons">
				<a data-lang="<?php echo LaravelLocalization::getCurrentLocale() ?>" href="<?php echo LaravelLocalization::localizeUrl($block->setting('login_url')) ?>" class="btn btn-primary">
					<?php echo __($block->setting('login'));?>
				</a>
				<a href="<?php echo LaravelLocalization::localizeUrl($block->setting('register_url')) ?>" class="btn btn-secondary">
					<?php echo __($block->setting('register'));?>
				</a>
			</div>
		</div>
		</div>
	</div>
	</div>
</section>