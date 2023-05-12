<div class="container-fluid mt-5 px-3 px-lg-5 pt-2">
    <div class="support-list">

    	<div class="table-title d-flex flex-column flex-lg-row justify-content-between p-3">
			<h1 class="m-0 p-0 text-center text-lg-left">{{__('My profile settings')}}</h1>
		</div>

        @if (session()->has('successImage'))
			<div class="alert alert-success mt-2 alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				{{ session('successImage') }}
			</div>
		@endif

		<div class="white-container my-4">
			<div class="row justify-content-center justify-content-lg-between">
				<div class="col-6 col-lg-3">
					<div class="image-upload d-flex flex-column">
						<figure class="image_profile">
							@if($image)
								@if (!$uploaded)
									<img class="rounded-circle img-fluid" src="{{$image}}"  alt="{{__('Profile image')}}"/>
								@else
									<img class="rounded-circle img-fluid" src="{{asset('storage/profile')}}/{{$profile_image}}"  alt="{{__('Profile image')}}"/>
								@endif
							@else
								@if ($profile_image)
									<img class="rounded-circle img-fluid" src="{{asset('storage/profile')}}/{{$profile_image}}"  alt="{{__('Profile image')}}"/>
								@else
									<img class="rounded-circle img-fluid" src="{{asset('/debugadmin/assets/images/faces/face8.jpg')}}"   alt="{{__('Profile image')}}">
								@endif
							@endif
							<i class="fas fa-pencil-alt profile"></i>
						</figure>
						<input type="file" id="file" name="profile_image" wire:model="profile_image" wire:change="$emit('fileChoosen')" :errors="$errors"  style="display:none"/>
						@if($image && !$uploaded)
							<a class="text-primary" wire:click="uploadImage">{{__('Save')}}</a>
						@endif

						@error('profile_image') <span class="error">{{ $message }}</span> @enderror
					</div>
				</div>
				<div class="col-lg-auto">
					<div class="row">
						<div class="col-lg-4 order-1 order-lg-1">
							<span class="font-weight-bold">{{__('Name')}}</span>
						</div>
						<div class="col-lg-4 order-3 order-lg-2">
							<span class="font-weight-bold">{{__('Created')}}</span>
						</div>
						<div class="col-lg-4 order-5 order-lg-3">
							<span class="font-weight-bold">{{__('Last Login')}}</span>
						</div>
						<div class="col-lg-4 order-2 order-lg-4">
							<span>{{ @$first_name ." ". @$last_name }}</span>
						</div>
						<div class="col-lg-4 order-4 order-lg-5">
							<span>{{ get_date($created) }}</span>
						</div>
						<div class="col-lg-4 order-6 order-lg-6">
							<span>{{ (!empty($lastLogin)) ? get_date($lastLogin) : '-' }}</span>
						</div>
					</div>

				</div>
			</div>
			<div class="w-100 d-flex justify-content-center my-4">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item m-1 m-lg-2">
						<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == "Personal") active @endif" wire:click="changeTab('Personal')" data-toggle="tab" href="#startpage-link">{{__('Personal details')}}</a>
					</li>
					<li class="nav-item m-1 m-lg-2">
						<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == "Login") active @endif" wire:click="changeTab('Login')" data-toggle="tab" href="#blog-sidebar-link">{{__('Login details')}}</a>
					</li>
                    @if($is_company)
                        <li class="nav-item m-1 m-lg-2">
                            <a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == "Company") active @endif" wire:click="changeTab('Company')" data-toggle="tab" href="#company">{{__('Company information')}}</a>
                        </li>
                    @endif
				</ul>
			</div>
			@if($tab == "Personal")
				@if (session()->has('success'))
					<div class="alert alert-success mb-15 alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						{{ session('success') }}
					</div>
				@endif

				<div class="row">
					<div class="form-group col-12 col-md-12">
						<label for="company" class="form-label">{{__('Company')}} @if($type == 'company') <span class="text-danger">*</span> @endif</label>
						<input id="company" wire:model="company" type="text" value="" class="form-control" :errors="$errors"  autocorrect="off" spellcheck="false" autocomplete="off">
						@error('company') <span class="error">{{ $message }}</span> @enderror
					</div>

					<div class="form-group col-12 col-md-6 ">
						<label for="first_name" class="form-label">{{__('First name')}} <span class="text-danger">*</span></label>
						<input id="first_name" wire:model="first_name" type="text" value="" class="form-control" :errors="$errors" autocomplete="off">
						@error('first_name') <span class="error">{{ $message }}</span> @enderror
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="last_name" class="form-label">{{__('Last name')}} <span class="text-danger">*</span></label>
						<input id="last_name" wire:model="last_name" type="text" value="" class="form-control" :errors="$errors" autocomplete="off">
						@error('last_name') <span class="error">{{ $message }}</span> @enderror
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="address" class="form-label">{{__('Address')}} <span class="text-danger">*</span></label>
						<input id="address" wire:model="address" type="text" value="" class="form-control" :errors="$errors" autocomplete="off">
						@error('address') <span class="error">{{ $message }}</span> @enderror
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="city" class="form-label">{{__('City')}} <span class="text-danger">*</span></label>
						<input id="city" wire:model="city" type="text" value="" class="form-control" :errors="$errors" autocomplete="off">
						@error('city') <span class="error">{{ $message }}</span> @enderror
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="countrySelected" class="form-label">{{__('Country')}} <span class="text-danger">*</span></label>
						<div>
							<select id="load-countries" class="form-control" :errors="$errors" >
								<option val="">{{__('Make a Choice')}}</option>
								@if(!empty($countries))
								@foreach($countries as $item)
								<option val="{{$item->id}}" @if($countrySelected == $item->id or $countrySelected == $item->name) selected="selected" @endif>{{$item->name}}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="postalcode" class="form-label">{{__('Postalcode')}} <span class="text-danger">*</span></label>
						<input id="postalcode" wire:model="postalcode" type="text" value="" class="form-control" :errors="$errors" autocomplete="off">
						@error('postalcode') <span class="error">{{ $message }}</span> @enderror
					</div>
                    <div class="form-group col-12 col-md-6">
                        <label for="type" class="form-label">{{__('Type')}}</label>
                        <select wire:model="type" id="type" class="form-control" :errors="$errors">
                            <option value="">{{__('Choose an option')}}</option>
                            <option value="user">{{__('User')}}</option>
                            <option value="company">{{__('Company')}}</option>
                        </select>
                        @error('type') <span class="error">{{ $message }}</span> @enderror
                    </div>
					<div class="form-group col-12 col-md-6">
                        <label for="language" class="form-label">{{__('Preferred language')}}</label>
                        <select wire:model="language" id="language" class="form-control" :errors="$errors">
                            <option value="">{{__('Choose an option')}}</option>
                            @if(!empty($languages))
                                @foreach($languages as $language)
                                    <option value="{{ $language->id }}">{{ $language->description }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('language') <span class="error">{{ $message }}</span> @enderror
                    </div>
				</div>
            @elseif($tab == "Company")
                @if (session()->has('success3'))
                    <div class="alert alert-success mb-15 alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('success3') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger mb-15 alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('error') }}
                    </div>
                @endif
                <form autocomplete="off" class="row" wire:submit.prevent="storeInlog" onkeydown="return event.key != 'Enter';">
                    <div class="form-group col-12 col-md-6">
                        <div class="form-group">
                            <label for="kvk_number" class="form-label">{{__('KVK Number')}}</label>
                            <input id="kvk_number" wire:model="kvk_number" type="text" class="form-control" :errors="$errors" autocomplete="off">
                            @error('kvk_number') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                   <div class="form-group col-12 col-md-6">
                        <div class="form-group">
                            <label for="tax" class="form-label">{{__('VAT number')}}</label>
                            <input id="tax" wire:model="vatno" type="text" class="form-control" :errors="$errors" autocomplete="off">
                            @error('vatno') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
			@else
				@if (session()->has('success2'))
					<div class="alert alert-success mb-15 alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						{{ session('success2') }}
					</div>
				@endif
				@if (session()->has('error'))
					<div class="alert alert-danger mb-15 alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						{{ session('error') }}
					</div>
				@endif
				<form autocomplete="off" class="row" wire:submit.prevent="storeInlog" onkeydown="return event.key != 'Enter';">
					<div class="col-12 col-sm-12 col-md-12">
						<div class="form-group">
							<label for="name" class="form-label">{{__('Username')}}</label>
							<input id="name" wire:model="name" type="text" value="" class="form-control" :errors="$errors" autocomplete="off">
							@error('name') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="form-group">
							<label for="email" class="form-label">{{__('Email')}}</label>
							<input id="email" wire:model="email" type="text" value="" class="form-control" :errors="$errors" autocomplete="off">
							@error('email') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="form-group">
							<label for="password" class="form-label">{{__('Password')}}</label>
							<input id="password" wire:model="password" type="password" value="" class="form-control" :errors="$errors" autocomplete="off">
							@error('password') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="form-group">
							<label for="password_confirm" class="form-label">{{__('Confirm Password')}}</label>
							<input autocomplete="new-password" id="password_confirm" wire:model="password_confirm" type="password" value="" class="form-control" :errors="$errors" autocomplete="off">
							@error('password_confirm') <span class="password_confirm">{{ $message }}</span> @enderror
						</div>
					</div>
				</form>
			@endif
			<div class="w-100 text-right">
				<div wire:loading.remove wire:target="storePersonal, storeInlog, storeCompany" >
					<button type="button" class="btn btn-primary"
                            @if($tab == "Personal")
                                wire:click="storePersonal()"
                            @elseif($tab == "Company")
                                wire:click="storeCompany()"
                            @else
                                wire:click="storeInlog()"
                            @endif>
                        {{__('Update Profile')}}
                    </button>
				</div>
			</div>

			<div class="w-100 text-left">
				<small class="text-dark"><span class="text-danger">*</span> {{__('Required fields')}}</small>
			</div>
		</div>
    </div>
</div>

@push('scripts')
	<script type="text/javascript">

		document.addEventListener('livewire:load', function () {
			$("body").on( "click", ".fa-pencil-alt.profile", function() {
				$('input#file').trigger('click');
			});

			$('#load-countries').on('change', function (e) {
				@this.set('countrySelected', $('#load-countries').val());
			});
		});

		window.livewire.on('fileChoosen', () => {
			const elements = document.getElementsByClassName('.error');
			while(elements.length > 0){
				elements[0].parentNode.removeChild(elements[0]);
			}
			let inputField = document.getElementById('file');
			let file = inputField.files[0];
			let reader = new FileReader();
			reader.onloadend = () => {
				window.livewire.emit('fileUpload', reader.result);
			}
			reader.readAsDataURL(file);
		});
	</script>
	@endpush
