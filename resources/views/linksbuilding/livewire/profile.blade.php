<div>
    <div wire:ignore.self class="modal profile" tabindex="-1" id="prof" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('My profile settings')}}</h5>
                    <button type="button" data-dismiss="modal" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (session()->has('successImage'))
                        <div class="alert alert-success mb-15 alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('successImage') }}
                        </div>
                    @endif
                    <div class="image-upload">
                        <figure  @if($image) @if (!$uploaded) class="example" @endif @endif >
                            @if($image)
                                @if (!$uploaded)
                                    <img src="{{$image}}" class="example"  alt="{{__('Profile image')}}" />
                                @else
                                    <img src="<?php echo Theme::url('uploads'); ?>/{{$profile_image}}"   class="mb-4 " alt="{{__('Profile image')}}" />
                                    <i class="fas fa-pencil-alt profile"></i>
                                @endif
                            @else
                                @if ($profile_image)
                                    <img src="<?php echo Theme::url('uploads'); ?>/{{$profile_image}}"   class="mb-4 " alt="{{__('Profile image')}}" />
                                    <i class="fas fa-pencil-alt profile"></i>
                                @else
                                    <img style="width:80px; border-radius: 100%;" src="{{asset('/debugadmin/assets/images/faces/face8.jpg')}}"  class="mb-4 " alt="{{__('Profile image')}}">
                                    <i class="fas fa-pencil-alt profile"></i>
                                @endif
                            @endif
                        </figure>

                        <input type="file" id="file" name="profile_image" wire:model="profile_image" wire:change="$emit('fileChoosen')" :errors="$errors"  style="display:none"/>

                        <span>
                                    <span class="profilename">{{$profile_name}}</span><br/>
                                    <span class="role">{{ auth()->user()->roles->description }}</span>
                               </span>
                        <span>
                                    <div class="cr">
                                        <?php $created = explode(' ', $created); ?>
                                        {{$created[0]}} {{$created[1]}}<br/>
                                    </div>
                                    <span class="created">{{__('Created')}}</span>
                               </span>
                        <span>
                                    <div class="cr">
                                     <?php $lastLogin = explode(' ', $lastLogin); ?>
                                        {{$lastLogin[0]}} {{$lastLogin[1]}}<br/>
                                    </div>
                                    <span class="created">{{__('Last Login')}}</span>
                               </span>
                    </div>

                    @if($image)
                        @if (!$uploaded)
                            <div class="row">
                            <span class="mt-2 blue" >
                                <a class="blue underlined" wire:click="uploadImage">{{__('Please upload now your profile image!')}}</a>
                            </span>
                            </div>
                        @endif
                    @endif

                    @error('profile_image') <span class="error">{{ $message }}</span> @enderror


                    @if (empty(session('subuser')))

                        <div class="tabs">
                            <div class="row">
                                <a wire:click="changeTab('Personal')" @if( $tab == "Personal") class="active" @endif>{{__('Personal details')}}</a> <a  wire:click="changeTab('Login')" @if( $tab == "Login") class="active" @endif >{{__('Login details')}}</a>
                            </div>
                        </div>
                        <br style="clear:both;" />
                        @if( $tab == "Personal")

                            @if (session()->has('success'))
                                <div class="alert alert-success mb-15 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form class="row" autocomplete="off" wire:submit.prevent="storePersonal" onkeydown="return event.key != 'Enter';">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="company" class="form-label">{{__('Company')}}</label>
                                        <input id="company" wire:model="company" type="text" value="" class="form-control" :errors="$errors"  autocorrect="off" spellcheck="false" autocomplete="ÑÖcompletes">
                                        @error('company') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="vatno" class="form-label">{{__('Vatno')}}</label>
                                        <input id="vatno" wire:model="vatno" type="text" value="" class="form-control" :errors="$errors" autocomplete="ÑÖcompletes">

                                    </div>
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">{{__('First name')}}</label>
                                        <input id="first_name" wire:model="first_name" type="text" value="" class="form-control" :errors="$errors" autocomplete="ÑÖcompletes">
                                        @error('first_name') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name" class="form-label">{{__('Last name')}}</label>
                                        <input id="last_name" wire:model="last_name" type="text" value="" class="form-control" :errors="$errors" autocomplete="ÑÖcompletes">
                                        @error('last_name') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="address" class="form-label">{{__('Address')}}</label>
                                        <input id="address" wire:model="address" type="text" value="" class="form-control" :errors="$errors" autocomplete="ÑÖcompletes">
                                        @error('address') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="postalcode" class="form-label">{{__('Postalcode')}}</label>
                                        <input id="postalcode" wire:model="postalcode" type="text" value="" class="form-control" :errors="$errors" autocomplete="ÑÖcompletes">
                                        @error('postalcode') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="city" class="form-label">{{__('City')}}</label>
                                        <input id="city" wire:model="city" type="text" value="" class="form-control" :errors="$errors" autocomplete="ÑÖcompletes">
                                        @error('city') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="countrySelected" class="form-label">{{__('Country')}}</label>
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
                                        <input id="name" wire:model="name" type="text" value="" class="form-control" :errors="$errors" autocomplete="ÑÖcompletes">
                                        @error('name') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">{{__('Email')}}</label>
                                        <input id="email" wire:model="email" type="text" value="" class="form-control" :errors="$errors" autocomplete="ÑÖcompletes">
                                        @error('email') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="form-label">{{__('Password')}}</label>
                                        <input id="password" wire:model="password" type="password" value="" onfocus="this.removeAttribute('readonly');" readonly class="form-control" :errors="$errors" autocomplete="ÑÖcompletes">
                                        @error('password') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirm" class="form-label">{{__('Confirm Password')}}</label>
                                        <input autocomplete="new-password" onfocus="this.removeAttribute('readonly');" readonly id="password_confirm" wire:model="password_confirm" type="password" value="" class="form-control" :errors="$errors" autocomplete="ÑÖcompletes">
                                        @error('password_confirm') <span class="password_confirm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </form>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <div wire:loading wire:target="storePersonal, storeInlog, uploadImage, changeTab" >
                        <img src="{{asset('img/loading-gif.gif')}}" class="loader">
                    </div>
                    @if (empty(session('subuser')))
                        <div wire:loading.remove wire:target="storePersonal, storeInlog" >
                            <button type="button" class="btn btn-primary" @if( $tab == "Personal") wire:click="storePersonal()"  @else wire:click="storeInlog()"  @endif >{{__('Update Profile')}}</button>
                        </div>
                    @endif
                    <button type="button" data-dismiss="modal" class="btn btn-secondary" >{{__('Cancel')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script type="text/javascript">
        //tabchange
        window.addEventListener('tabchange', event => {
            $('#select2-dropdown').select2();
            $('#select2-dropdown').on('change', function (e) {
                var selected = $('#select2-dropdown').select2("id");
                @this.set('countrySelected', selected);
            });
        });

        document.addEventListener('livewire:load', function () {
            $('input').attr('autocomplete', 'off');
            $('#select2-dropdown').select2();
            $('#select2-dropdown').on('change', function (e) {
                var selected = $('#select2-dropdown').select2("val");
                @this.set('countrySelected', selected);
            });
            $("body").on( "click", ".fa-pencil-alt.profile", function() {
                $('input#file').trigger('click');
            });

            $('#load-countries').on('change', function (e) {
                @this.set('countrySelected', $('#load-countries').val());
            });
        });

        window.livewire.on('fileChoosen', () => {

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
