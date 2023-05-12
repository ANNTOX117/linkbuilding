<!-- <a href="{{ route('login') }}" class="btn btn-primary">
    Login
</a> -->
<a <?php if($block->setting('is_login')): ?> href="<?php echo LaravelLocalization::localizeUrl('/login') ?>" <?php elseif($block->setting('is_registration')): ?> href="<?php echo LaravelLocalization::localizeUrl('/register') ?>" <?php else: ?> href="<?php echo LaravelLocalization::localizeUrl($block->setting('url')) ?>" <?php endif; ?> 
    class="btn <?php if($block->setting('primary')): ?> btn-primary <?php else: ?> btn-secondary <?php endif; ?>">
    <?php echo __($block->setting('text'));?>
</a>
