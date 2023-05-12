$(document).ready(function() {
    if($('.adjust-text').length) {
        $('.adjust-text').each(function(){
            // adjustTextToBackground(this);
            $(this).css('color', getTextColor(this));
            // $('this').attr('style', function(i,s) { return s + ';color: '+getTextColor(this)+' !important;' });
        });
    }
    // $('a').each(function(){
    //     // $(this).css('color', getTextColor(this));
    //     $('this').attr('style', function(i,s) { return s + ';color: '+getTextColor(this)+' !important;' });
    // });
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
    lightness >= 0.60 ? $('#navbar-page-header').addClass('navbar-light') : $('#navbar-page-header').addClass('navbar-dark');
    $(element).children('*').each(function(){
        lightness >= 0.60 ? $(this).children('*').css('color', 'black') : $(this).children('*').css('color', 'white');    
    });
    lightness >= 0.60 ? $(element).children('*').css('color', 'black') : $(element).children('*').css('color', 'white');
    return lightness >= 0.60 ? $(element).css('color', 'black') : $(element).css('color', 'white');
}

function getTextColor (element, lightColor = '#FFFFFF', darkColor = '#000000') {
    
    var backgroundColor = $(element).css('background-color');
    if(backgroundColor)
    {
        backgroundColor =  backgroundColor.split(',');
        var R = parseInt(backgroundColor[0].split('(')[1]);
        var G = parseInt(backgroundColor[1]);
        var B = parseInt(backgroundColor[2].split(')')[0]);
    
        var bgColor = RGB2Color(R,G,B);
        const getLuminance = function (hexColor) {
          var color = (hexColor.charAt(0) === '#') ? hexColor.substring(1, 7) : hexColor
          var r = parseInt(color.substring(0, 2), 16) // hexToR
          var g = parseInt(color.substring(2, 4), 16) // hexToG
          var b = parseInt(color.substring(4, 6), 16) // hexToB
          var uicolors = [r / 255, g / 255, b / 255]
          var c = uicolors.map(col => col <= 0.03928 ? col / 12.92 : ((col + 0.055) / 1.055) ** 2.4)
      
          return (0.2126 * c[0]) + (0.7152 * c[1]) + (0.0722 * c[2]);
        }
      
        var L = getLuminance(bgColor)
        var L1 = getLuminance(lightColor)
        var L2 = getLuminance(darkColor)
    }
    

    $(element).children('*').each(function(){
        // console.log($(this).children('*'), this, $(this).children, $(this), $(this).children('*').length);
        if($(this).children('*').length > 0)
            $(this).children('*').css('color', getTextColor($(this).children('*')));
            // $(this).children('*').attr('style', function(i,s) { return s + ';color: '+getTextColor($(this).children('*'))+' !important;' });
    });
  
    return (L > Math.sqrt((L1 + 0.05) * (L2 + 0.05)) - 0.05) ? darkColor : lightColor;
  }

function RGB2Color(r,g,b)
{
  return '#' + this.byte2Hex(r) + this.byte2Hex(g) + this.byte2Hex(b);
}

function byte2Hex (n)
{
  var nybHexString = "0123456789ABCDEF";
  return String(nybHexString.substr((n >> 4) & 0x0F,1)) + nybHexString.substr(n & 0x0F,1);
}