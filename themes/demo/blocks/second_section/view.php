<section class="section section--blocks">
	<div class="container">
	<!-- [block slug="h3-title"] -->
	<h3 class="title__section text-center" data-aos="fade-up" style="margin-bottom: 45px !important;">
		<?=$block->setting('title_section_0')?>
	</h3>
	<div class="row">
		<div class="col-md-4">
		<div class="block" data-aos="fade-up">
			<!-- [block slug="h3-title"] -->
			<h3 class="title__block">
				<?php echo __($block->setting('title_section_1'));?>
			</h3>
			<figure class="block__figure">
			<img src="<?=phpb_theme_asset('img/icon1.png');?>" alt="" class="block__figure__image">
			</figure>
			<!-- [block slug="paragraph"] -->
			<p class="paragraph">
				<?php echo __($block->setting('paragraph_1'));?>
			</p>
		</div>
		</div>
		<div class="col-md-4">
		<div class="block" data-aos="fade-up">
			<!-- [block slug="h3-title"] -->
			<h3 class="title__block">
				<?php echo __($block->setting('title_block_1'));?>
			</h3>
			<figure class="block__figure">
			<img src="<?=phpb_theme_asset('img/icon2.png');?>" alt="" class="block__figure__image">
			</figure>
			<!-- [block slug="paragraph"] -->
			<p class="paragraph">
				<?php echo __($block->setting('paragraph_2'));?>
			</p>
		</div>
		</div>
		<div class="col-md-4">
		<div class="block" data-aos="fade-up">
			<!-- [block slug="h3-title"] -->
			<h3 class="title__block">
				<?php echo __($block->setting('title_block_2'));?>
			</h3>
			<figure class="block__figure">
			<img src="<?=phpb_theme_asset('img/icon3.png');?>" alt="" class="block__figure__image">
			</figure>
			<!-- [block slug="paragraph"] -->
			<p class="paragraph">
				<?php echo __($block->setting('paragraph_3'));?>
			</p>
		</div>
		</div>
	</div>
		<div class="dec">
		<figure class="decoration">
			<img src="<?=phpb_theme_asset('img/decoration-two.png');?>" alt="">
		</figure>
		</div>
	</div>
</section>
