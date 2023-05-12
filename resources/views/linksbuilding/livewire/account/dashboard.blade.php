<div class="container-fluid mt-5 px-3 px-lg-5 pt-2">
    <div class="table-title row m-0 justify-content-lg-between">
        <h1 class="col-12 col-md-auto text-center">{{__('Hi')}}, {{ Auth::user()->name }}</h1>
        @if($is_empty)
            <div class="col-12 col-lg-auto d-flex flex-column flex-md-column flex-lg-row align-items-center text-muted table-subtitle m-0 mt-0 mb-3 my-lg-3">
                <p class="m-0">{{__('You don\'t have any link bought yet')}}</p>
                <a class="btn btn-primary mx-2 mt-2 mt-lg-0" href="{{ route('customer_buylinks') }}">{{ __('Buy your first link')}}</a>
            </div>
        @endif
    </div>

	<div class="row mt-4">
		<div class="col-12 col-md-4 col-lg-4 col-xl-4 mb-3">
			<div class="card dashboard-bg-default-card h-100">
				<div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
					<h3>{{ __('Active links')}}</h3>
					<a class="number stretched-link" href="{{ route('customer_links') }}?tab=2">{{ $active }}</a>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4 col-lg-4 col-xl-4 mb-3">
			<div class="card dashboard-bg-default-card h-100">
				<div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
					<h3>{{ __('Links about to expire')}}</h3>
					<a class="number stretched-link" href="{{ route('customer_links') }}?tab=3">{{ $about }}</a>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4 col-lg-4 col-xl-4 mb-3">
			<div class="card dashboard-bg-default-card h-100">
				<div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
					<h3>{{ __('Expired links')}}</h3>
					<a class="number stretched-link" href="{{ route('customer_links') }}?tab=4">{{ $expire }}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-4">
		<div class="col-md-4 mb-3">
			<div class="card card-gray h-100">
				<div class="card-header">
                    <i class="fas fa-file-alt"></i> {{ __('Latest invoices')}}
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
					<a href="{{ route('customer_orders') }}">{{ __('All orders')}}</a>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-3 staffels">
			<div class="card card-gray h-100">
				<div class="card-header">
                    <i class="fas fa-percent"></i> {{ __('Volume discount')}}
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

		<div class="col-md-4 mb-3 promo">
			<div class="card bg-gradient-blue h-100 text-white text-center">
				<div class="card-header">
                    <i class="fas fa-tags"></i> {{ __('Featured website')}}
				</div>
				<div class="card-body text-center">
					<div class="d-flex flex-column align-items-center justify-content-center h-100 text-white">
						<p class="h4 text-break">{{ $site->url }}</p>
						@if ($site->price > $site->price_special)
							<del class="text-muted h4">
								{{ get_price($site->price) }}
							</del>
						@endif
						<p class="dashboard-price h4">
							@if (!is_null($site->price_special) && $site->price > $site->price_special)
								{{ get_price($site->price_special) }}
							@else
								{{ get_price($site->price) }}
							@endif
						</p>
						<span class="pl-2"><strong>{{ __('IP:')}} </strong>{{ $site->subnet}}</span>
						<div class="d-flex">
							<span class="pl-2"><strong>{{__('DA:')}}</strong>{{ $site->pa }}</span>
							<span class="pl-2"><strong>{{__('PA:')}}</strong>{{ $site->da }}</span>
							<span class="pl-2"><strong>{{__('TF:')}}</strong>{{ $site->tf }}</span>
							<span class="pl-2"><strong>{{__('CF:')}}</strong>{{ $site->cf }}</span>
						</div>
						<a href="{{ route('customer_buylinks') }}" class="btn btn-primary mt-3 text-white">
							{{ __('Buy link')}}
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
