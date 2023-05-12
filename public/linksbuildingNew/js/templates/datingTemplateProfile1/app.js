(function(code){
	code(window.jQuery,window,document);
}(function($,window,document){

	$("#scroll-up").click(function() {
     $([document.documentElement, document.body]).animate({
         scrollTop: $("#mainHeader").offset().top
     }, 600);
 });

 $(document).on('click','#open-filters', function(){
	 $('.nabar-right').toggleClass('open-nav');
 });

 window.onload = (event) =>{
	var $grid = $('.grid').masonry({
	  // options
	  itemSelector: '.grid-item'
	});
	$grid.masonry('layout');
  };
 
 
	$('.owl-carousel-profiles').owlCarousel({
	 loop:true,
	 margin:15,
	 nav:true,
	 dots:false,
	 autoplay: true,
	 navText : ["<i class='fas fa-arrow-left'></i>","<i class='fas fa-arrow-right'></i>"],
	 responsive:{
			 0:{
					 items:1
			 },
			 576:{
					 items:3
			 },
			 768:{
					 items:4
			 },
			 992:{
					 items:6
			 },
			 1200:{
					 items:8
			 }
	 }
});

	AOS.init();

  dataBackground();
  var match = new bootstrap_equalizer();
	match.init();

  function dataBackground(){
		$('*[data-background]').each(function(){
			var element = $(this);
			var bgUrl = element.attr('data-background');
			element.css('background-image','url('+bgUrl+')');
			element.css('background-repeat','no-repeat');
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
							if(item.height() > maxHeight[i]){
								maxHeight[i] = item.height();
							}
						});

						wrapper.find('[data-equalizer-level="'+levels[i]+'"]').each(function() {
							var item = $(this);
							item.height(maxHeight[i]);
						});
					}
				}
				wrapper.addClass('equalizer-done');
			});
		}
	}
	afixHead(50);
	function afixHead($offset){
		if($(window).scrollTop() > $offset){
			$('body').addClass('fixed-bar');
		}else{
			$('body').removeClass('fixed-bar');
		}

		$(window).scroll(function(){
			if($(window).scrollTop() > $offset){
				$('body').addClass('fixed-bar');
			}else{
				$('body').removeClass('fixed-bar');
			}

			var stickyEl = $('.wp-block-column:nth-child(2) .promo-block');
			stickyEl.parent().css('position','relative');
			var pW =  stickyEl.parent().width();
			if(stickyEl.length){
				if($(window).scrollTop() > stickyEl.parent().offset().top){
					stickyEl.addClass('sticky').css('width', pW+'px');
				}else{
					stickyEl.removeClass('sticky').css('width', pW+'px');;
				}
			}


		});
	}
}));
