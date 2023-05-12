
$( document ).ready(function() {



	$(document).on('click','.passwordforgot' ,function(event) {

	
		event.preventDefault();
		$('button.passwordforgot').hide();

		$.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		 });

		$.ajax({
			url: "/request-new-password",
			type:"post",
			dataType: "json",
			data: {
				'email': $('.email').val(),
				'_token':  $('meta[name="_token"]').attr('content')
			},
			beforeSend: function(){
				$('.overlay').addClass('modal-container');
				$('div.loader').show();
		},
			complete: function(){
				$('.overlay').removeClass('modal-container');
				$('div.loader').hide('slow');
			},
			success: function( data ) {
				if (data.error){
					$('div.msg').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+data.error+'</div>');
				}
				if (data.success){
					$('div.msg').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+data.success+'</div>');				
				}
				$('button.passwordforgot').show();
			}
		});
	});


});

