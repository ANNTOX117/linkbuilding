<div class="container-fluid mt-5 px-3 px-lg-5 pt-2">
	<div class="support-list">
		<div class="table-title d-flex flex-column flex-lg-row justify-content-between p-3">
			<h1 class="m-0 p-0 text-center text-lg-left">{{__('Settings')}}</h1>
		</div>
		@if (session()->has('successupdate'))
			<div class="alert alert-success mt-2 alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				{{ session('successupdate') }}
			</div>
		@endif
		<div class="white-container mt-4">
			@if (!empty($options))
				@foreach ($options as $option)
					<div class="form-group mb-1">
						<input type="checkbox" wire:model="checked.{{ $option->id }}">
						<label>{{__($option->option)}}</label>
					</div>
				@endforeach
			@endif
			<div class="w-100 text-right">
				<button type="button" class="btn btn-primary"  wire:click="updatesettingsuser()" >{{__('Update Settings')}}</button>
			</div>
		</div>
	</div>
</div>
