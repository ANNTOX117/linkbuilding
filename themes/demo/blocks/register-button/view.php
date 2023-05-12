<!-- <a href="{{ route('login') }}" class="btn btn-primary">
    Login
</a> -->
<a href="<?php echo LaravelLocalization::localizeUrl('/register') ?>" class="btn <?php if($block->setting('primary')): ?> btn-primary <?php else: ?> btn-secondary <?php endif; ?>">
    <?php echo __('Register');?>
</a>
