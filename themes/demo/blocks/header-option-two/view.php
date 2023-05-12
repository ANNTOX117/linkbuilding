<section id="section-one" class="section section--hero">
  <figure class="figure">
    <img src="<?=$block->setting('image')?>" alt="">
  </figure>
  <figure class="decoration">
    <img src="<?=phpb_theme_asset('img/decoration.png');?>" alt="">
  </figure>
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="content" data-aos="fade-right">
          <h1 class="title__main" style="margin-top: 60px;">
            <?php echo __($block->setting('title'));?>
          </h1>
          [block slug="paragraph"]
        </div>
      </div>
    </div>
  </div>
</section>