$(document).ready(function() {
    if($('.adjust-text').length) {
        $('.adjust-text').each(function(){
            adjustTextToBackground(this);
        });
    }
});

$(document).on('click', '.cookies-container button.agree', function (e){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'get',
        url: '/cookies',
        timeout: 10000,
        complete: function() {
            $('.cookies-wrapper').hide()
        }
    });
});

function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return 'rgb('+ parseInt(result[1], 16) +','+ parseInt(result[2], 16) +','+ parseInt(result[3], 16) +')';
}

function adjustTextToBackground(element) {
    var backgroundColor = $(element).css('background-color');
    backgroundColor =  backgroundColor.split(',');
    var R = parseInt(backgroundColor[0].split('(')[1]);
    var G = parseInt(backgroundColor[1]);
    var B = parseInt(backgroundColor[2].split(')')[0]);
    var rPrime = R/255;
    var gPrime = G/255;
    var bPrime = B/255;
    var cMax = Math.max(rPrime, gPrime, bPrime);
    var cMin = Math.min(rPrime, gPrime, bPrime);
    var lightness = (cMax + cMin)/2;
    return lightness >= 0.50 ? $(element).css('color', 'black') : $(element).css('color', 'white');
}
