<div id="dashboard" class="row">
	<div class="col-lg-6 mb-5">
		<div class="bg-white p-4 shadow">
			<h3 class="font-weight-bold">{{__('Welcome')}}, {{ Auth::user()->name }}!</h3>
			<div class="d-flex justify-content-between align-items-center">
				<p class="m-0">{{__('You don\'t have any link bought yet')}}.</p>
				<div class="d-flex flex-column">
					<div class="nav-link mx-2 px-2 text-black mb-2 text-center">
						<h6 class="m-0">Uw tegoed : {{ currency() }} {{ auth()->user()->credit }}</h6>
					</div>
					<a class="btn btn-primary mx-2 mt-2 mt-lg-0" href="{{ route('customer_buylinks') }}">{{ __('Buy your first link')}}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 mb-5">
		<div class="bg-white d-flex align-items-center p-4 shadow h-100">
			<div class="d-flex justify-content-between align-items-center w-100">
				<div>
					<h5>{{ __('Active links')}}</h5>
					<p class="m-0">{{__('Active links')}}.</p>
				</div>
				<a class="number stretched-link h1 text-success" href="{{ route('customer_links') }}?tab=2">{{ $active }}</a>
			</div>
		</div>
	</div>

	<div class="col-lg-6 mb-5">
		<div class="bg-white d-flex align-items-center justify-content-between p-4 shadow">
			<div>
				<h5>{{ __('Links about to expire')}}</h5>
				<p>{{ __('Links about to expire')}}</p>
			</div>
			<a class="number stretched-link h1 text-warning" href="{{ route('customer_links') }}?tab=3">{{ $about }}</a>
		</div>
	</div>
	<div class="col-lg-6 mb-5">
		<div class="bg-white d-flex align-items-center justify-content-between p-4 shadow">
			<div>
				<h5>{{ __('Links about to expire')}}</h5>
				<p>{{ __('Links about to expire')}}</p>
			</div>
			<a class="number stretched-link h1 text-danger" href="{{ route('customer_links') }}?tab=3">{{ $about }}</a>
		</div>
	</div>
	<div class="col-md-6 col-lg-6 col-xl-4 mb-5">
		<div class="card bg-white border-0 h-100 shadow">
			<div class="card-header bg-white border-0">
				<i class="fas fa-file-alt"></i> {{ __('Latest invoices')}}
				<hr class="mb-0 mt-3">
			</div>
			<div class="card-body">
				@if($orders->isEmpty())
					<p class="text-center text-muted"><em>{{ __('No invoices found.')}}</em></p>
					@if($is_empty)
						<a href="{{ route('customer_buylinks') }}" class="btn btn-primary mb-2">{{ __('Buy your first link')}}</a>
						<a href="{{ route('customer_buylinks') }}#articles" class="btn btn-primary mb-2">{{ __('Buy your first article')}}</a>
					@endif
				@else
				@if(!empty($orders))
					<table class="table">
						<tbody>
							<tr>
								<th class="border-top-0 pt-0">{{ __('Number')}}</th>
								<th class="border-top-0 pt-0">{{ __('Invoice')}}</th>
							</tr>
							@foreach($orders as $order)
							<tr>
								<td>{{ $order->invoice }}</td>
								<td>
									@if(!empty($order->invoice))
									<a href="{{ route('download_invoice', ['name' => $order->order]) }}" class="btn btn-primary btn-block" download>{{__('Invoice')}}</a>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				@endif
				@endif
			</div>
			<div class="card-footer text-center bg-white">
				<a class="text-dark" href="{{ route('customer_orders') }}">{{ __('All orders')}}</a>
			</div>
		</div>		
	</div>
	<div class="col-md-6 col-lg-6 col-xl-4 mb-5">
		<div class="card bg-white border-0 h-100 shadow">
			<div class="card-header bg-white border-0">
				<i class="fas fa-percent"></i> {{ __('Volume discount')}}
				<hr class="mb-0 mt-3">
			</div>
			<div class="card-body">
				<p>{!! __('Save euros by using the volume discount. Volume discount will be applied when purchasing new links as well as renewed links. The percentages are set out below.') !!}</p>
				<table class="table">
					<tbody>
						<tr>
							<th>{{ __('Products')}}</th>
							<th>{{ __('Discount')}}</th>
						</tr>
						@if(!empty($from))
							@foreach($from as $key => $value)
								<tr>
									<td>@if(empty($to[$key])) > @endif {{ $from[$key] }} @if(!empty($to[$key])) - {{ $to[$key] }} {{ plural_or_singular('item', $to[$key]) }} @else {{ plural_or_singular('item', $to[$key]) }} @endif</td>
									<td>{{ integer_or_float($percentage[$key]) }}%</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-lg-6 col-xl-4 mb-5">
		<div class="card bg-white border-0 h-100 shadow">
			<div class="card-header bg-white border-0">
				<i class="fas fa-tags"></i> {{ __('Featured website')}}
				<hr class="mb-0 mt-3">
			</div>
			<div class="card-body">
				@if (!empty($site))
					<div class="d-flex flex-column h-100 text-dark">
						<p class="h4 text-break">{{ $site->url }}</p>
						<div class="dashboard-price d-flex">
							<span class="pr-2"><strong>{{__('Price:')}}</strong></span>
							@if ($site->price > $site->price_special)
								<del class="text-muted mr-2">
									{{ get_price($site->price) }}
								</del>
							@endif
							@if (!is_null($site->price_special) && $site->price > $site->price_special)
								{{ get_price($site->price_special) }}
							@else
								{{ get_price($site->price) }}
							@endif
						</div>
						<span class="pr-2 mt-2"><strong>{{ __('IP:')}} </strong>{{ $site->subnet}}</span>
						<div class="d-flex mt-2">
							<span class="pr-2"><strong>{{__('DA:')}}</strong>{{ $site->pa }}</span>
							<span class="pr-2"><strong>{{__('PA:')}}</strong>{{ $site->da }}</span>
							<span class="pr-2"><strong>{{__('TF:')}}</strong>{{ $site->tf }}</span>
							<span class="pr-2"><strong>{{__('CF:')}}</strong>{{ $site->cf }}</span>
						</div>
						<a href="{{ route('customer_buylinks') }}" class="btn btn-primary mt-3 text-capitalize">
							{{ __('Buy link')}}
						</a>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script>

</script>
@endpush
