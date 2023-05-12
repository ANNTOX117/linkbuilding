<div>
	<div class="links-kopen">
		<div class="container-fluid">
			<div class="row">
				@if (!$invoce)
					<div class="col-md-12 my-3">
						<div class="alert alert-primary" role="alert">
							{{ __('Please provide your billing information in order to make a purchase.')}}  <a href="{{ route('customer_profile') }}">{{__('Update')}}</a>
						</div>
					</div>
				@endif
				<div class="position-relative">
					<div wire:loading wire:target="openImport, addRowStartingpage, addStartingpage, table">
						<img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader bulk-loader" />
					</div>
					@if ($status_form_startingpage != '')
						<div class="alert @if ($status_form_startingpage == 'fail') alert-warning @else alert-success @endif " role="alert">
							<small>{{ $message_status_startingpage }}</small>
						</div>
					@endif
					<div class="box d-flex flex-column align-items-start">
						<div id="showstartingpage" class="yesshow">
							<div class="table-title mt-2 mb-4">
								<h4 class="my-4 font-weight-bold">{{__('Configure your link')}}</h4>
							</div>
							<div class="card card-gray">
								<div class="card-body">
									<div class="row justify-content-center mb-3">

										<div class="col-12 col-lg-auto mr-auto pt-2">
											<div class="btn-group">
												<a class="btn btn-block btn-secondary" href="{{ route('download_addbulk') }}">{{ trans('Download template CSV') }}</a>
												<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
													{{ trans('Options CSV') }}
												</button>
											</div>
										</div>
										<div class="col-12 col-lg-auto pt-2">
											<input class="form-control mr-2 input-file" type="file" wire:model="csv">
										</div>
										<div class="col-12 col-lg-auto pt-2">
											<button class="btn btn-block btn-secondary" wire:click="openImport">{{ trans('Import CSV') }}</button>
										</div>
										<div class="col-12 text-center text-lg-right">
											<span id="message-input" class="error w-100 pr-3 text-right d-none">{{ trans('You need to select a file') }}</span>
										</div>

										<div class="col-12">
											<div class="collapse" id="collapseExample">
												<div class="card card-body">
													<div class="table-responsive">
														<table class="table table-hover">
															<tr>
																<td>Link</td>
																<td>Anchor</td>
																<td>Follow</td>
																<td>Title</td>
																<td>Target</td>
															</tr>
															<tr>
																<td>https://www.link.com</td>
																<td>Anchor</td>
																<td>follow or nofollow</td>
																<td>Title</td>
																<td>_black or empty</td>
															</tr>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id="accordion">
										@if (!empty($inputs))
											@foreach ($inputs as $key => $item)
												<div class="card">
													<div class="card-header d-flex align-items-center justify-content-between">
														<button class="btn {{ ($key == $accord_selected) ? 'collapsed' : '' }}" data-toggle="collapse" data-target="#collapse-1-{{ $key }}" aria-expanded="false" aria-controls="collapse-1-{{ $key }}" wire:click="selectAccordion({{ $key }})">
															{{ __('Startpage link') }} {{ $key + 1 }}
														</button>
														@if ($item['status'] == 'empty')
															<i class="far fa-circle"></i>
														@elseif($item['status'] == 'correct')
															<i class="far fa-check-circle text-success"></i>
														@else
															<i class="far fa-times-circle text-danger"></i>
														@endif
													</div>
													<div id="collapse-1-{{ $key }}" class="collapse {{ ($key == $accord_selected) ? 'show' : '' }}" data-parent="#accordion">
														<div class="card-body">
															<div class="row">
																<div class="col-lg-6 form-group">
																	<label class="form-label text-aqua">{{__('Website')}}</label>
																	<div class="md-select section1 bulk" data-key="{{ $key }}" wire:ignore>
																		<button type="button" class="ng-binding d-flex justify-content-between align-items-center m-0" data-content="startpage_link_list">
																			{{ __('Sites')}}
																		</button>
																		<ul role="listbox"></ul>
																	</div>
																	{!! @search_errors('site_startingpage', @$validator[$key]) !!}
																</div>
																<div class="col-lg-6 form-group">
																	<label class="form-label text-aqua">{{__('Section')}}</label>
																	<div wire:ignore>
																		<select id="category-startingpageactive-{{ $key }}" class="form-control custom-select min-53" wire:model="inputs.{{$key}}.section_startingpage">
																			<option value="">{{ __('Categories')}}</option>
																		</select>
																	</div>
																	{!! @search_errors('section_startingpage', @$validator[$key]) !!}
																</div>
																<div class="col-lg-6 form-group">
																	<div class="row align-items-end m-0">
																		<div class="col-12 col-lg-12 px-0 form-group">
																			<label class="form-label text-aqua">{{__('Link URL')}}</label>
																			<input wire:model="inputs.{{$key}}.starting_url" type="text" class="form-control">
																			{!! @search_errors('starting_url', @$validator[$key]) !!}
																		</div>
																		<div class="col-12 col-lg px-0" wire:ignore>
																			<label class="form-label text-aqua">{{__('Follow')}}</label>
																			<select wire:model="inputs.{{$key}}.starting_follow" class="form-control custom-select">
																				<option value="1">{{ __('follow') }}</option>
																				<option value="2">{{ __('nofollow') }}</option>
																			</select>
																		</div>
																		<div class="col-12 col-lg px-0 px-lg-2 pt-2" wire:ignore>
																			<label class="form-label text-aqua">{{__('Target')}}</label>
																			<select wire:model="inputs.{{$key}}.starting_blank" class="form-control custom-select">
																				<option value="">{{ __('Same tab') }}</option>
																				<option value="_blank">{{ __('New tab') }}</option>
																			</select>
																		</div>
																		<div class="col-12 col-lg-1 px-0 pt-2 text-center">
																			<a class="btn btn-block btn-sm bg-light p-2" target="_blank" href="{{ prefix_http(@$inputs[$key]['starting_url']) }}">
																				<i class="fas fa-external-link-alt p-0 m-1"></i>
																			</a>
																		</div>
																	</div>
																</div>
																<div class="col-lg-6">
																	<div class="form-group">
																		<label class="form-label text-aqua">{{__('Anchor')}}</label>
																		<div class="">
																			<input wire:model="inputs.{{$key}}.starting_anchor" type="text" class="form-control">
																		</div>
																		{!! @search_errors('starting_anchor', @$validator[$key]) !!}
																	</div>
																	<div class="form-group pt-2">
																		<label class="form-label text-aqua">{{__('Title')}}</label>
																		<div class="">
																			<input wire:model="inputs.{{$key}}.starting_title" type="text" class="form-control" placeholder="{{ __('Optional') }}">
																		</div>
																		{!! @search_errors('starting_title', @$validator[$key]) !!}
																	</div>
																</div>
																<div class="col-lg-4 form-group">
																	<label class="form-label text-aqua">{{__('Preview')}}</label>
																	<div class="">
																		<input type="text" class="form-control" disabled="disabled" value="{{ get_string_bulk($inputs[$key]['starting_url'],$inputs[$key]['starting_title'],$inputs[$key]['starting_anchor'],$inputs[$key]['starting_follow'],$inputs[$key]['starting_blank'] )}}">
																	</div>
																</div>
																<div class="col-lg-4 form-group">
																	<label class="form-label text-aqua">{{__('Publication date')}}</label>
																	<div class="input-group date" wire:ignore>
																		<input type="text" class="form-control datepicker date-input pl-3" placeholder="{{__('Publication date')}}"  data-provide="datepicker" data-date-format="dd/mm/yyyy" onchange="Livewire.emit('dateStartingpage', {{ $key }}, this.value)" autocomplete="off">
																		<div class="input-group-addon">
																			<span class="glyphicon glyphicon-th"></span>
																		</div>
																	</div>
																	{!! @search_errors('expired_startingpage', @$validator[$key]) !!}
																</div>
																<div class="col-lg-4 form-group">
																	<label class="form-label text-aqua">{{__('Time active')}}</label>
																	<div>
																		<select wire:model="inputs.{{$key}}.yearstartingpage" class="form-control custom-select yearsstartingpage">
																			<option value="">{{__('Select the years')}}</option>
																			<option value="1">{{__('1 year')}}</option>
																			<option value="2">{{__('2 years')}}</option>
																			<option value="3">{{__('3 years')}}</option>
																			<option value="4">{{__('4 years')}}</option>
																			<option value="5">{{__('5 years')}}</option>
																		</select>
																	</div>
																	{!! @search_errors('yearstartingpage', @$validator[$key]) !!}
																</div>
															</div>
														</div>
													</div>
												</div>
											@endforeach
										@endif
									</div>
									
								</div>
								<div class="bg-secondary text-center mx-5 mb-3">
									<button class="btn btn-block" wire:click="addRowStartingpage">
										<i class="fas fa-plus text-white"></i>
									</button>
								</div>
								<div class="modal-footer">
									<a href="javascript:void(0)" wire:click="addStartingpage" class="btn btn-primary"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="list" class="d-none">
		<div id="startpage_link_list">
			@if(!empty($startpage_link_list))
				@foreach($startpage_link_list as $site)
					<li id="{{ $site->id }}" role="option" class="ng-binding li-starting" tabindex="-1" aria-selected="false">
						<div class="w-100 d-flex flex-column flex-md-column flex-lg-row justify-content-between content-startingpage m-0" data-site="{{ $site->id }}">
							<span class="col-lg-auto px-0 text-center">
								{{ $site->url }}
							</span>
							<span class="col-lg-auto text-center">
								@if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
									<del class="text-muted">
										{{ currency() }} {{ get_price($site->price) }}
									</del>
								@else
									<strong>
										{{ currency() }} {{ get_price($site->price) }}
									</strong>
								@endif

								@if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
									<strong class="text-danger">
										{{ currency() }} {{ get_price($site->price_special) }}
									</strong>
								@endif
							</span>
							<span class="col-lg-auto ml-auto text-center px-0">
								{{ $site->subnet }}
							</span>
							<small class="d-flex justify-content-center col-lg-auto text-center px-0">
								<strong class="mx-1">PA</strong>{{ round_price($site->pa) }}
								<strong class="mx-1">DA</strong>{{ round_price($site->da) }}
								<strong class="mx-1">TF</strong>{{ round_price($site->tf) }}
								<strong class="mx-1">CF</strong>{{ round_price($site->cf) }}
							</small>
						</div>
					</li>
				@endforeach
			@endif
		</div>
	</div>
</div>

@push('scripts')
<script>
	
	$(document).ready( function () {
		
		$('.date-input').datepicker({
			startDate: '+1d',
			format: 'dd/mm/yyyy'
		});

		$(document).on('click','.ng-binding' ,function(event) {
			var div = $(this).data('content');
			$(this).parent().find('ul').append($('#'+div).html());
			$(this).closest('.section1').toggleClass('active');
		});
		
		$(document).on('click','.li-starting' ,function(event) {
			$(this).closest('.section1').find('button').empty().append($(this).html());
			var data = {
				id: $(this).closest('.row').find('.min-53').attr('id'), 
				value: $(this).find('.content-startingpage').data('site'),
				key : $(this).closest('.section1').data('key')
			};
			window.livewire.emit('changestartingpage', data);
		});
	});
	
	(function($){
		window.addEventListener('updateList', event => {
			if (event.detail.errors) {
				alert('Error');
			}
			else{
				var text = "@lang('Categories')";
				$('#'+event.detail.item).empty().append($('<option>').val("").text(text));


				console.log(event.detail.categories);	
				

				$.each(event.detail.categories, function(index, val) {
					$('#'+event.detail.item).append($('<option>').val(val.id).text(val.name));
				});
			}
		});

		window.addEventListener('updateDatepicker', event => {
			$('.date-input').datepicker({
				startDate: '+1d',
				format: 'dd/mm/yyyy'
			});
		});

		window.addEventListener('hideFormcontent', event => {
			var sites = lang('Sites');
			$('.input-file').val('');
			$('.section1.bulk button').empty().text(sites);
		});
		
		window.addEventListener('showImport', event => {
			if(event.detail.message) {
				$('#message-input').removeClass('d-none');
			}
		});

		window.addEventListener('doComplete', event => {
			dialogConfirm(event.detail.message, event.detail.confirm, event.detail.cancel, event.detail.redirect);
		});

	})(jQuery);
</script>
@endpush




