<div>
	<div class="bg-white p-4 shadow">
		<div class="container-fluid">
			<div class="support-list">
				<h4 class="m-0 p-0 my-4 text-center text-lg-left">{{__('Settings')}}</h4>
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
	</div>
</div>
