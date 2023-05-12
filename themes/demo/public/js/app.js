AOS.init();
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

		$("#arrow").click(function() {
		    $([document.documentElement, document.body]).animate({
		        scrollTop: $("#section-one").offset().top
		    }, 300);
		});
