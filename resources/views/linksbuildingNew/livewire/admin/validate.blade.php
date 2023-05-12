@section('title')
	{{ $title }}
@endsection

<div>
	<div class="content-wrapper center">
		@if(!empty($title))
			<div class="row page-title-header">
				<div class="col-12">
					<div class="page-header">
						<h4 class="page-title">{{ $title }}</h4>
					</div>
				</div>
			</div>
		@endif
		<div class="cont">
			<div class="row justify-content-end align-items-center mb-3">
				<div class="col-12 col-lg-auto">
					<input class="form-control mr-2 input-file p-1" type="file" wire:model="csv">
				</div>
				<div class="col-12 col-lg-auto">
					<button class="btn btn-block btn-secondary p-2" wire:click="openImport">{{ trans('Import CSV') }}</button>
				</div>
				<div class="col-12 text-center text-lg-right">
					<span id="message-input" class="error w-100 pr-3 text-right d-none">{{ trans('You need to select a file') }}</span>
				</div>
			</div>
			<div class="card">
				<div class="card-body">

					<div class="table-responsive">
						<table class="table table-hover table-rules">
							<thead>
								<tr>
									<th>{{__('ID')}}</th>
									<th>{{__('TO')}}</th>
									<th>{{__('FROM')}}</th>
									<th>{{__('STATUS')}}</th>
								</tr>
							</thead>
							<tbody>
								@if (!empty($table))
									@foreach($table as $item)
										<tr>
											<td>{{ $item['id'] }}</td>
											<td>{{ $item['linkto'] }}</td>
											<td>{{ $item['linkfrom'] }}</td>
											<td>
												@if ($item['result'] == 1)
													{{ __('Follow') }}
													<i class="fas fa-check ml-2"></i>
												@elseif($item['result'] == 2)
													{{ __('Empty page ') }}
													<i class="fas fa-times ml-2"></i>
												@elseif($item['result'] == 3)
													{{ __('Invalid url') }}
													<i class="fas fa-times ml-2"></i>
												@else
													{{ __('No follow') }}
													<i class="fas fa-times ml-2"></i>
												@endif
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div wire:loading wire:target="openImport">
			<img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
		</div>
	</div>
</div>
