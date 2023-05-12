
@push('stylescss')


<style>
.grid {
	min-height: 100vh;
	display: grid;
	grid-gap: 30px;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	grid-auto-rows: 150px;
	grid-auto-flow: row dense;
	line-height: 20px;
}
.item {
	position: relative;
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-ms-flex-direction: column;
	flex-direction: column;
	-webkit-box-pack: end;
	-ms-flex-pack: end;
	justify-content: flex-end;
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
	background: #0c9a9a;
	color: #fff;
	grid-column-start: auto;
	grid-row-start: auto;
	color: #fff;
}
.level-2 {
	grid-row-end: span 2;
}
.level-3 {
	grid-row-end: span 3;
}
.level-4 {
	grid-row-end: span 4;
}
.level-5 {
	grid-row-end: span 5;
}
.level-6 {
	grid-row-end: span 6;
}


@media screen and (max-width: 800px) {
	.grid {
		display: block;
	}
}

</style>

@endpush

<div>
	<div class="row justify-content-center">
		<div class="col-10 my-3">
			<nav class="navbar navbar-expand-lg navbar-light header-site-color shadow justify-content-between">
				<a class="navbar-brand text-uppercase font-weight-bold d-flex align-items-center" href="#">
					<img class="mr-1" height="25" src="{{ asset($site->logo) }}">
					{{ $site->name }}
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarText">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item">
							<a class="nav-link h5 text-dark" href="#">{{ __('Home')}}</a>
						</li>
						<li class="nav-item">
							<a class="nav-link h5 text-dark" href="#">{{ __('Dochters')}}</a>
						</li>
						<li class="nav-item">
							<a class="nav-link h5 text-dark" href="#">{{ __('Contact')}}</a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
		<div class="col-10">
			<div class="grid">
				@foreach ($site->categories as $category)
					<div class="card @if (count($category->links) > 4 ) level-2 @elseif (count($category->links) > 8 ) level-3 @elseif (count($category->links) > 12) level-4 @elseif (count($category->links) > 16) level-5 @elseif (count($category->links) > 20) level-6 @endif">
						<div class="card-header text-center border-0 px-0 py-2">
							<button class="btn site-color btn-block text-white"><h3>{{ $category->name }}</h3></button>
						</div>
						@if (count($category->links) > 0)
							<div class="card-body border p-4">
								<ul class="d-flex flex-column justify-content-between h-100">
									@foreach ($category->links as $item)
										<li class="link-site-color">
											<a class="link-site-color" href="{{ $item->url }}">{{ $item->anchor}}</a>
										</li>
									@endforeach
								</ul>
							</div>
						@endif
					</div>
				@endforeach
			</div>
		</div>
	</div>
	<footer class="footer site-color mt-5">
		<div class="container">
			<div class="row">
				<div class="col-md-8 my-3">
					<h4 class="text-white font-weight-bold">{{ $site->name }}</h4>
					<p class="m-0 text-white">{{ __('Bespaar tot 60% van jouw tijd op jouw linkbuilding door gebruik te maken van onz tool') }} </p>
				</div>
				<div class="col-md-4 text-white my-3">
					<h6><i class="fas fa-angle-double-right mr-2"></i>{{__('Dashboard')}}</h6>
					<h6><i class="fas fa-angle-double-right mr-2"></i>{{__('My account')}}</h6>
					<h6><i class="fas fa-angle-double-right mr-2"></i>{{__('Help Center')}}</h6>
					<h6><i class="fas fa-angle-double-right mr-2"></i>{{__('Ask for support')}}</h6>
				</div>
			</div>
		</div>
        <div class="copyright">
            {{__('All rights reserved Copyright')}} &copy; {{date('Y')}}
        </div>
	</footer>
</div>

@push('scripts')
    <script>
		$(document).ready( function () {
			$('.site-color').css('background-color', '{{$site->box}}');
			$('.link-site-color').css('color', '{{$site->links}}');
			$('.header-site-color').css('background-color', '{{ $site->menu}}');
		});
	</script>
@endpush

