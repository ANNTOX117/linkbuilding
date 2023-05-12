<h3 class="<?php if($block->setting('section')): ?> title__section <?php else: ?> title__block <?php endif; ?>
    <?php if($block->setting('center')): ?> text-center" data-aos="fade-up" <?php else: ?> " <?php endif; ?>>
        <?php echo __($block->setting('text'));?>
</h3>