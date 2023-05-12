(function(code){
	code(window.jQuery,window,document);
}(function($,window,document){

	particlesJS.load('particles-js', '/js/particles.json', function() {
	  console.log('callback - particles.js config loaded');
	});
	particlesJS.load('particles-js-2', '/js/particles.json', function() {
	  console.log('callback - particles.js config loaded');
	});

	$(document).on('click', '.toggleFilters', function(){
		$('.sidebar').toggleClass('open');
	});

	$(document).on('click', '.navbar-toggler', function(){
		$('body').toggleClass('menu-open');
	});

	$('.widget').on('click', 'h4', function(){
		$(this).parent().find('ul').toggleClass('open');
	});

	$('.sidebar .filter').on('click', 'h5', function(){
		$(this).parent().find('>ul, >div').toggleClass('open');
	});

	$('.owl-three-items').owlCarousel({
			loop:	true,
			margin:	0,
			stagePadding: 0,
			nav:	false,
			navText: ['<i class="icofont-simple-left"></i>','<i class="icofont-simple-right"></i>'],
			dots:	true,
			autoplay: false,
			responsive:	{
				0:{
					items: 1
				},
				992:{
					items: 3
				},
				1200:{
					items: 3
				}
			}
		});


	dataBackground();

    var match = new bootstrap_equalizer();
		match.init();

    function dataBackground(){
		$('*[data-background]').each(function(){
			var element = $(this);
			var bgUrl = element.attr('data-background');
			element.css('background-image','url('+bgUrl+')');
			element.css('background-repeat','no-repeat');
			if(hasAttr(element,'data-background-repeat')){
				element.css('background-repeat',element.attr('data-background-repeat'));
			}
			if(hasAttr(element,'data-background-size')){
				element.css('background-size',element.attr('data-background-size'));
			}
			if(hasAttr(element,'data-background-attachment')){
				element.css('background-attachment',element.attr('data-background-attachment'));
			}
			if(hasAttr(element,'data-background-position')){
				element.css('background-position',element.attr('data-background-position'));
			}
		});
	}

  function bootstrap_equalizer(){
  this.init = function(){
    var $this = this;
    setTimeout(function(){
      $this.match();
    },300);

    $(window).resize(function(){ $this.match(); });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      $this.match();
    });
  }
  this.match = function(){
    $('[data-equalizer]').each(function(){
      var wrapper = $(this), maxHeight = new Array(), breakpoint, levels = new Array();

      if(wrapper.hasClass('equalizer-done')){wrapper.removeClass('equalizer-done');}

      switch(wrapper.attr('data-equalizer-mq')){
        case 'xs':
          breakpoint = 0;
          break;
        case 'sm':
          breakpoint = 767;
          break;
        case 'md':
          breakpoint = 991;
          break;
        case 'lg':
          breakpoint = 1023;
          break;
        default:
          breakpoint = 0;
          break;
      }

      wrapper.find('[data-equalizer-watch]').css('height','auto');

      if($(window).width() > breakpoint){

        wrapper.find('[data-equalizer-watch]').each(function() {
          var item = $(this), level;

          if(hasAttr(item,'data-equalizer-level')){
            level = parseInt(item.attr('data-equalizer-level'));
            if(!(levels.includes(level))){
              levels.push(level);
            }
          }
        });
        if(levels.length == 0){
          levels.push(1);
          wrapper.find('[data-equalizer-watch]').attr('data-equalizer-level',1)
        }

        for(var i = 0; i< levels.length; i++) {
          maxHeight.push(0);
          wrapper.find('[data-equalizer-level="'+levels[i]+'"]').each(function() {
            var item = $(this);
            if(item.outerHeight() > maxHeight[i]){
              maxHeight[i] = item.outerHeight();
            }
          });

          wrapper.find('[data-equalizer-level="'+levels[i]+'"]').each(function() {
            var item = $(this);
            item.outerHeight(maxHeight[i], false);
          });
        }
      }
      wrapper.addClass('equalizer-done');
    });
  }
}

$.fn.extend({
	hasClasses: function (selectors) {
			var self = this;
			for (var i in selectors) {
					if ($(self).hasClass(selectors[i]))
							return true;
			}
			return false;
	}
});

	//Helpers
	function hasAttr(element,attr){
		var hasattr = element.attr(attr);

		if (typeof hasattr !== typeof undefined && hasattr !== false) {
			return true;
		}else{
			return false;
		}
	}
}));
