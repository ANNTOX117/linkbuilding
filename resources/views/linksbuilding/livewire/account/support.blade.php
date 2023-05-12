<div class="container-fluid mt-5 px-3 px-lg-5 pt-2">
	<div class="support-list">
		<div class="table-title d-flex flex-column flex-lg-row justify-content-between p-3">
			<h1 class="m-0 p-0 text-center text-lg-left">{{__('Contact opnemen')}}</h1>
		</div>
		<div class="white-container my-4">
			<div class="row justify-content-center">
				<div class="col-lg-12">
					@if (session()->has('successsupport'))
						<div class="alert alert-success mt-2 alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('successsupport') }}
						</div>
					@endif

					<p>{{__('support_message')}}</p>

					<div class="form-group row">
						<label class="col-sm-2 col-form-label">{{ __('Name')}}</label>
						<div class="col-sm-10">
							<input wire:model="full_name" type="text" class="form-control" disabled="disabled">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">{{ __('Email')}}</label>
						<div class="col-sm-10">
							<input wire:model="email" type="text" class="form-control" disabled="disabled">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">{{__('Message')}}</label>
						<div class="col-sm-10">
							<textarea wire:model.lazy="message" class="form-control" rows="10" name="message"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-2"></div>
						<div class="col-sm-10">
							<button wire:click="sendsupport()" type="submit" class="btn btn-primary float-right">{{ __('Send')}}</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
