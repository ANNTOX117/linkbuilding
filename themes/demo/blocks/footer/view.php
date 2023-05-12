<footer>
	<button id="arrow"><i class="fa-regular fa-chevron-up"></i></button>
	<div class="widgets">
		<div class="container">
			<div class="row">
				<div class="col-lg-5">
					<div class="widget">
					<figure class="logo">
						<a href="#">
						<img src="<?=phpb_theme_asset('img/logo.png');?>" alt="">
						</a>
					</figure>
					[block slug="paragraph"]
					<!-- <p class="paragraph">
						Vestibulum purus quam, scelerisque ut, mollis sed, nonummy id, metus. Nullam accumsan lorem in dui. Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia.
					</p> -->
					</div>
				</div>
				<div class="col-lg-2 ms-auto mr-auto">
					<div class="widget">
						<ul>
							<li>
								<a href="<?php echo LaravelLocalization::localizeUrl($block->setting('first_url_1')) ?>">
									<?php echo __($block->setting('first_text_1'));?>
								</a>
							</li>
							<li>
								<a href="<?php echo LaravelLocalization::localizeUrl($block->setting('first_url_2')) ?>">
									<?php echo __($block->setting('first_text_2'));?>
								</a>
							</li>
							<li>
								<a href="<?php echo LaravelLocalization::localizeUrl($block->setting('first_url_3')) ?>">
									<?php echo __($block->setting('first_text_3'));?>
								</a>
							</li>
							<li>
								<a href="<?php echo LaravelLocalization::localizeUrl($block->setting('first_url_4')) ?>">
									<?php echo __($block->setting('first_text_4'));?>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2 ms-auto mr-auto">
					<div class="widget">
						<ul>
							<li>
								<a href="<?php echo LaravelLocalization::localizeUrl($block->setting('second_url_1')) ?>">
									<?php echo __($block->setting('second_text_1'));?>
								</a>
							</li>
							<li>
								<a href="<?php echo LaravelLocalization::localizeUrl($block->setting('second_url_2')) ?>">
									<?php echo __($block->setting('second_text_2'));?>
								</a>
							</li>
							<li>
								<a href="<?php echo LaravelLocalization::localizeUrl($block->setting('second_url_3')) ?>">
									<?php echo __($block->setting('second_text_3'));?>
								</a>
							</li>
							<li>
								<a href="<?php echo LaravelLocalization::localizeUrl($block->setting('second_url_4')) ?>">
									<?php echo __($block->setting('second_text_4'));?>
								</a>
							</li>
							<li>
								<a href="<?php echo LaravelLocalization::localizeUrl($block->setting('second_url_5')) ?>">
									<?php echo __($block->setting('second_text_5'));?>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="bottom">
		<div class="container">
			<span><?php echo __('Linkbuildings - All rights reserved Copyright © 2022'); ?> </span>
		</div>
	</div>
</footer>