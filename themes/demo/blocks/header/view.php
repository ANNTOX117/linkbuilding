<header>
    <nav class="navbar navbar-expand-lg navbar">
      <div class="container">
        <a class="navbar-brand logo" href="/">
        <img src="<?=phpb_theme_asset('img/logo.png')?>">
        </a>
        <button class="navbar-toggler ms-auto collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fa-solid fa-horizontal-rule"></i>
          <i class="fa-solid fa-horizontal-rule"></i>
          <i class="fa-solid fa-horizontal-rule"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul id="main-manu" class="navbar-nav">
            <!-- [block slug="li-nav-item"]
            [block slug="li-nav-item"]
            [block slug="li-nav-item"] -->
            <li class="nav-item">
              <a href="<?php echo LaravelLocalization::localizeUrl($block->setting('url_1')) ?>" class="nav-link">
                  <?php echo __($block->setting('text_1'));?>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo LaravelLocalization::localizeUrl($block->setting('url_2')) ?>" class="nav-link">
                  <?php echo __($block->setting('text_2'));?>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo LaravelLocalization::localizeUrl($block->setting('url_3')) ?>" class="nav-link">
                  <?php echo __($block->setting('text_3'));?>
              </a>
            </li>
            <li class="nav-item li-flag">
            <?php
								foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties){
						?>
									<a class="nav-link" data-hreflang="<?php echo $localeCode ?>" href="<?php echo LaravelLocalization::getLocalizedURL($localeCode, null, [], true) ?>">
                  <img src="https://flagicons.lipis.dev/flags/4x3/<?php echo $localeCode == 'en' ? 'gb' : $localeCode; ?>.svg" alt="">
									</a>
            <?php
              }
            ?>
            </li>
          </ul>
        </div>
      </div>
      <div class="dropdown filter drop-language">
        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <?php echo strtoupper(LaravelLocalization::getCurrentLocale()) ?> <img src="https://www.tippelstraat.nl/escort2/img/flag-<?php echo LaravelLocalization::getCurrentLocale() ?>.png" alt=""> <i class="fa fa-chevron-down"></i>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
					<?php
					  foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties){
					?>
            <li>
              <a class="dropdown-item" data-hreflang="<?php echo $localeCode ?>" href="<?php echo LaravelLocalization::getLocalizedURL($localeCode, null, [], true) ?>">
                <?php echo ucfirst($properties['native']); ?>
              </a>
            </li>	
          <?php
            }
          ?>
        </ul>
      </div>
    </nav>
</header>