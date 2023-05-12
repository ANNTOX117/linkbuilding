$('#password').keypress(function(event) {
    if(event.keyCode === 13) {
        $('.btn-login').click();
    }
});
$('#password_confirmation').keypress(function(event) {
    if(event.keyCode === 13) {
        $('.btn-register').click();
    }
}); 
// $(document).on('click', '.dropdown-item', function() {
//     console.log($(this).data('href'));
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('#_token').val()
//         }
//     });
//     $.ajax({
//         type: 'post',
//         url: '/change_language',
//         data: {
//             next_url: $(this).data('href'),
//             code_lang: $(this).data('hreflang'),
//         },
//         dataType: 'json',
//         timeout: 10000,
//         success: function(response){
//             window.location.href = request.responseText;
//             if(response.login) {
//                 window.location.href = response.url;
//                 $('#error_show').text('');
//                 $('#login_error').addClass('d-none');
//             }
//             $('.btn-login').removeClass('disabled');
//             $('.fakeMasonry-loader').addClass('d-none');
//         },
//         complete: function() {
//             $('.btn-login').show();
//         }
//     });
// });
$(document).on('click', '#dropdownMenu1', function() {
    $('.dropdown-menu').css('display') == 'block' ? $('.dropdown-menu').css('display', 'none') : $('.dropdown-menu').css('display', 'block');
});
$(document).on('focus', '.dropdown-menu', function() {
    $('.dropdown-menu').css('display', 'block');
});
$(document).on('focusout', '.dropdown-menu', function() {
    $('.dropdown-menu').css('display', 'none');
});
$(document).on('click', '.btn-login', function() {
    $('.btn-login').addClass('disabled');
    var lang = $(this).data('lang');
    var route = $(this).data('href');
    var email    = $('input[id="login"]').val();
    var password = $('input[id="password"]').val();

    $('.btn-login').addClass('disabled');
    $('.fakeMasonry-loader').removeClass('d-none');
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        }
    });
    $.ajax({
        type: 'post',
        url: '/login',
        data: {
            email: email,
            lang: lang,
            password: password
        },
        dataType: 'json',
        timeout: 10000,
        beforeSend: function() {
            $('.btn-login').addClass('disabled');
            $('.fakeMasonry-loader').removeClass('d-none');
        },
        success: function(response){
            window.location.href = request.responseText;
            if(response.login) {
                window.location.href = response.url;
                $('#error_show').text('');
                $('#login_error').addClass('d-none');
            }
            $('.btn-login').removeClass('disabled');
            $('.fakeMasonry-loader').addClass('d-none');
        },
        error: function(request,status,errorThrown) {
            $('.btn-login').removeClass('disabled');
            $('.fakeMasonry-loader').addClass('d-none');
            $('#error_show').text('');
            $('#login_error').addClass('d-none');
            if(request.status != 200){
                var fail = jQuery.parseJSON(request.responseText);
                var line_errors = '';
                $.each( fail.errors, function( key, value ) {
                    $.each(value, function( ite, val ) {
                        line_errors = line_errors+val+'<br>';
                    });
                });
                $('#error_show').html(line_errors);
                $('#login_error').removeClass('d-none');
            } else {
                window.location.href = request.responseText;
            }
        },
        complete: function() {
            $('.btn-login').show();
        }
    });
});

$(document).on('click', '.btn-register', function() {
    var name = $('#name').val();
    var lastname = $('#lastname').val();
    var login = $('#login').val();
    var password = $('#password').val();
    var password_confirmation = $('#password_confirmation').val();

    $('.btn-register').addClass('disabled');
    $('.fakeMasonry-loader').removeClass('d-none');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        }
    });
    $.ajax({
        type: 'post',
        url: '/register',
        data: {
            name: name,
            lastname: lastname,
            email: login,
            password: password,
            password_confirmation: password_confirmation
        },
        dataType: 'json',
        timeout: 10000,
        beforeSend: function() {
            // $('.btn-login').hide();
            $('#register_error').addClass('d-none');
            $('.btn-register').addClass('disabled');
            $('.fakeMasonry-loader').removeClass('d-none');
        },
        success: function(response){
            window.location.href = '/email/verify';
            if(response.login) {
                window.location.href = response.url;
                $('#error_show').text('');
                $('#register_error').addClass('d-none');
            }
            $('.btn-register').removeClass('disabled');
            $('.fakeMasonry-loader').addClass('d-none');
        },
        error: function(request,status,errorThrown) {
            $('.btn-register').removeClass('disabled');
            $('.fakeMasonry-loader').addClass('d-none');
            $('#error_show').html('');
            $('#register_error').addClass('d-none');
            if(request.status != 200){
                var fail = jQuery.parseJSON(request.responseText);
                var line_errors = '';
                $.each( fail.errors, function( key, value ) {
                    $.each(value, function( ite, val ) {
                        line_errors = line_errors+val+'<br>';
                    });
                });
                $('#error_show').html(line_errors);
                $('#register_error').removeClass('d-none');
            } else {
                window.location.href = '/email/verify';
            }
        },
        complete: function() {
            $('.btn-register').show();
        }
    });
});

$(document).on('click', '.forgot-password', function() {
    $('#login').modal('hide');
    $('#forgot').modal('show');

    $('.forgot-email').show();
    $('.forgot-code').hide();
    $('.forgot-new').hide();
});

$(document).on('click', '.btn-forgot', function() {
    var email = $('form[name="forgot"] input[name="email"]').val();

    $('.btn-forgot').addClass('disabled');
    $('.fakeMasonry-loader').removeClass('d-none');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/account/forgot',
        data: {
            email: email
        },
        dataType: 'json',
        timeout: 10000,
        beforeSend: function() {
            // $('.btn-forgot').hide();
            // $('.btn-forgot-loading').show();
            $('.btn-forgot').addClass('disabled');
            $('.fakeMasonry-loader').removeClass('d-none');
        },
        success: function(response){
            $('.btn-forgot').removeClass('disabled');
            $('.fakeMasonry-loader').addClass('d-none');
            if(response.forgot) {
                $('p.forgot-success').text(response.message);
                $('p.forgot-success').show();
                $('p.forgot-error').text('');
                $('p.forgot-error').hide();
                $('.forgot-email').hide();
                $('.forgot-code').show();
                $('.forgot-new').hide();
            } else {
                $('p.forgot-success').text('');
                $('p.forgot-success').hide();
                $('p.forgot-error').text(response.message);
                $('p.forgot-error').show();
                $('.forgot-email').show();
                $('.forgot-code').hide();
                $('.forgot-new').hide();
            }
        },
        complete: function() {
            $('.btn-forgot').removeClass('disabled');
            $('.fakeMasonry-loader').addClass('d-none');
            // $('.btn-forgot').show();
            // $('.btn-forgot-loading').hide();
        }
    });
});

$(document).on('click', '.btn-code', function() {
    var email = $('form[name="forgot"] input[name="email"]').val();
    var code  = $('form[name="forgot"] input[name="code"]').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/account/code',
        data: {
            email: email,
            code: code
        },
        dataType: 'json',
        timeout: 10000,
        beforeSend: function() {
            $('.btn-code').hide();
            $('.btn-code-loading').show();
        },
        success: function(response){
            if(response.code) {
                $('p.code-error').text('');
                $('p.code-error').hide();
                $('p.forgot-success').hide();
                $('.forgot-email').hide();
                $('.forgot-code').hide();
                $('.forgot-new').show();
            } else {
                $('p.code-error').text(response.message);
                $('p.code-error').show();
                $('.forgot-email').hide();
                $('.forgot-code').show();
                $('.forgot-new').hide();
            }
        },
        complete: function() {
            $('.btn-code').show();
            $('.btn-code-loading').hide();
        }
    });
});

$(document).on('click', '.btn-password', function() {
    var email        = $('form[name="forgot"] input[name="email"]').val();
    var password     = $('form[name="forgot"] input[name="new_password"]').val();
    var confirmation = $('form[name="forgot"] input[name="confirm_password"]').val();

    $('.btn-password').addClass('disabled');
    $('.fakeMasonry-loader').removeClass('d-none');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/account/reset',
        data: {
            email: email,
            password: password,
            confirmation: confirmation
        },
        dataType: 'json',
        timeout: 10000,
        beforeSend: function() {
            // $('.btn-password').hide();
            // $('.btn-password-loading').show();
            $('.btn-password').addClass('disabled');
            $('.fakeMasonry-loader').removeClass('d-none');
        },
        success: function(response){
            $('.btn-password').removeClass('disabled');
            $('.fakeMasonry-loader').addClass('d-none');
            if(response.password) {
                window.location.href = response.url;
                $('p.password-error').text('');
                $('p.password-error').hide();
            } else {
                $('p.password-error').text(response.message);
                $('p.password-error').show();
            }
        },
        complete: function() {
            $('.btn-password').removeClass('disabled');
            $('.fakeMasonry-loader').addClass('d-none');
            // $('.btn-password').show();
            // $('.btn-password-loading').hide();
        }
    });
});

$('#login').on('show.bs.modal', function (event) {
    let color = $(event.relatedTarget).css('background-color');

    if(color === null || color === 'undefined') {
        color = 'rgba(108,117,125)';
    }

    $('.modal-body .forgot-password').css('color', color);
    $('.modal-body .btn-login, .modal-body .btn-login-loading, .modal-body .btn-forgot, .modal-body .btn-forgot-loading, .modal-body .btn-code, .modal-body .btn-code-loading, .modal-body .btn-password, .modal-body .btn-password-loading').css('background-color', color);
});

$('#newsletter').submit(function(e){
    e.preventDefault();
    $('.btn-subscribe').click();
});

$(document).on('click', '.btn-subscribe', function(e) {
    e.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: $('form#newsletter').attr('action'),
        data: {
            email: $('form#newsletter input[name="email"]').val()
        },
        dataType: 'json',
        timeout: 10000,
        beforeSend: function() {
            $('.btn-subscribe').hide();
            $('.btn-subscribe-loading').show();
        },
        success: function(response){
            dialogAlert(response.message, 'OK');
        },
        complete: function() {
            $('.btn-subscribe').show();
            $('.btn-subscribe-loading').hide();
        }
    });
});

$(document).on('click', 'form[name="donations"] input[name="donation"]', function() {
    $('form[name="donations"] input[name="other_donation"]').val('');
});

$(document).on('change', 'form[name="donations"] input[name="other_donation"]', function() {
    var _value = $(this).val().replace(/\D/g, "");
    if(parseFloat(_value) > 0) {
        $('form[name="donations"] input[name="donation"]').prop('checked', false);
    } else {
        $('form[name="donations"] input[name="donation"]').prop('checked', true);
    }
});

$(document).on('click', '.form-extras', function() {
    $('.extra-fields').show();
    $(this).attr('type', 'submit').removeClass('form-extras');
});

$(document).on('click', '.btn-form-contact', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: '/contact',
        data: {
            name: $('form input[name="name"]').val(),
            email: $('form input[name="email"]').val(),
            subject: $('form input[name="subject"]').val(),
            message: $('form textarea[name="message"]').val()
        },
        dataType: 'json',
        timeout: 10000,
        beforeSend: function() {
            $('.btn-form-contact').hide();
            $('.btn-contact-loading').show();
        },
        success: function(response){
            if(response.errors) {
                $('p.contact-error').text(response.message);
                $('p.contact-error').show();
            } else {
                $('p.contact-error').text('');
                $('p.contact-error').hide();

                $('p.contact-success').text(response.message);
                $('p.contact-success').show();

                $('#contact')[0].reset();
            }
        },
        complete: function() {
            $('.btn-form-contact').show();
            $('.btn-contact-loading').hide();
        }
    });
});

$(document).on('click', '.share-link', function() {
    var social = $(this).data('social');

    if(social == 'whatsapp'){
        popup('https://wa.me/?text=' + window.location.href, 'Deel op Whatsapp', 550, 450);
    }

    if(social == 'email'){
        window.open('mailto:?&body='+ window.location.href, 'Deel via email');
    }

    if(social == 'facebook'){
        popup('https://www.facebook.com/sharer.php?quote=' + window.location.href, 'Deel op Facebook', 550, 450);
    }

    if(social == 'twitter'){
        popup('https://twitter.com/share?text=' + window.location.href, 'Deel op Twitter', 550, 450);
    }

    if(social == 'linkedin'){
        popup('https://www.linkedin.com/shareArticle?mini=true&url=' + window.location.href, 'Deel op Linkedin', 550, 450);
    }
});

$("input.currency-input").on({
    keyup: function() {
        formatCurrency($(this));
    },
    blur: function() {
        //
    }
});

function popup(url, title, width, height){
    var top  = (screen.height/2)-(height/2);
    var left = (screen.width/2)-(width/2);
    window.open(url, title, 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,width='+width+',height='+height+',top='+top+',left='+left);
}

function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.

    // get input value
    var input_val = input.val();

    // don't validate empty input
    //if (input_val === "") { return; }

    // original length
    var original_len = input_val.length;

    // initial caret position
    var caret_pos = input.prop("selectionStart");

    // check for decimal
    if (input_val.indexOf(".") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(".");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);

        // On blur make sure 2 numbers after decimal
        if (blur === "blur") {
            //right_side += "00";
        }

        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val = "€" + left_side; // + "." + right_side;

    } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);

        // final formatting
        if (blur === "blur") {
            //input_val += ".00";
        }
    }

    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
}
