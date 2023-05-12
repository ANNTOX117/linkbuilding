<section class="section section--content">
	<div class="container">
	<div class="row">
		<div class="col-lg-6">
		<div class="content" data-aos="fade-right">
			<h3 class="title__section">
				<?php echo __($block->setting('h3-title-section'));?>
			</h3>
			<!-- <p class="paragraph">
				<?php echo __($block->setting('paragraph'));?>
			</p> -->
			[block slug="paragraph"]
		</div>
		</div>
		<div class="col-lg-6">
		<table data-aos="fade-left">
			<tr>
			<th>
				<h4><?php echo __($block->setting('h4_th_1'));?> <br> <span> <?php echo __($block->setting('h4_th_1_1'));?></span></h4>
			</th>
			<th>
				<p>
					<?php echo __($block->setting('p_th_1'));?>
				</p>
			</th>
			</tr>
			<tr>
			<th>
				<h4><?php echo __($block->setting('h4_th_2'));?>  <br> <span><?php echo __($block->setting('h4_th_2_2'));?> </span></h4>
			</th>
			<th>
				<p>
					<?php echo __($block->setting('p_th_2'));?>
				</p>
			</th>
			</tr>
			<tr>
			<th>
				<h4><?php echo __($block->setting('h4_th_3'));?> <br> <span> <?php echo __($block->setting('h4_th_3_3'));?></span></h4>
			</th>
			<th>
				<p>
					<?php echo __($block->setting('p_th_3'));?>
				</p>
			</th>
			</tr>
		</table>
		</div>
	</div>
	</div>
	<figure class="decoration">
	<img src="<?=phpb_theme_asset('img/decoration-three.png');?>" alt="">
	</figure>
</section>