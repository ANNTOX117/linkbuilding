$(document).ready(function(){
    var waypoints = $('#counterup').waypoint(function(direction) {
        animateValue("valueOne", 0, 2000, 1500);
        animateValue("valueTwo", 0, 1200, 1500);
        animateValue("valueThree", 0, 1500, 1500);

        this.destroy();
    })

    function animateValue(id, start, end, duration) {
        if (start === end) return;
        var range = end - start;
        var current = start;
        var increment = end > start? 2 : -1;
        var stepTime = Math.abs(Math.floor(duration / range));
        var obj = document.getElementById(id);
        var timer = setInterval(function() {
            current += increment;
            obj.innerHTML = current;
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }

    var targetDiv = $('body');
    var targetHeader = $('body');
    var div = $("#counterup");
    $(window).scroll(function() {

        var windowpos = $(window).scrollTop();
        if( windowpos >= 101 ) {
            targetDiv.addClass('scrolling-active');
        } else {
            targetDiv.removeClass('scrolling-active');
        }

    });

    $(document).on('click',"#title_owl",function(e) {
        var id =  $(this).attr('data-id');
        console.log(id);
        $('.description.owl').removeClass('active');
        $("#description_"+id).addClass("active");
        return false;
    });

    $('.owl-services').owlCarousel({
        loop:true,
        margin:5,
        nav:true,
        dots:false,
        autoplay: true,
        autoplayTimeout:2500,
        responsive:{
            0:{
                items:1
            },
            768:{
                items:2
            },
            1200:{
                items:3
            }
        }
    });

});
