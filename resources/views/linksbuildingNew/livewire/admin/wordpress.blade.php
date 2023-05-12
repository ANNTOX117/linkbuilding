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

        <div class="topbar">
            <div class="left bold">
                @if(permission('wordpress', 'create'))
                    <a data-toggle="modal" wire:click="modalAddSite"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add site')}}</span></a>
                @endif
            </div>
        </div>

        <div class="cont ">
            <div class="card">
                <div class="card-body">

                    <div class="white-container form-inline mt-2 w-100 mb-3">
                        <div class="col-12 col-md-auto col-lg-auto d-flex align-items-center mb-3 m-md-0 m-lg-0">
                            {{ __('Per Page:') }}&nbsp;
                            <select wire:model="pagination" class="form-control select-xs inputs-small ml-2">
                                <option>2</option>
                                <option>5</option>
                                <option>10</option>
                                <option>15</option>
                                <option>25</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-auto col-lg-auto text-center ml-auto">
                            <input wire:model.debounce.300ms="search" class="form-control inputs-small" type="text" placeholder="{{__('Search')}}...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-templates">
                            <thead>
                            <tr>
                                <th>{{__('Name')}} <a wire:click="sort('name')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('URL')}} <a wire:click="sort('url')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Type')}} <a wire:click="sort('type')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('IP')}} <a wire:click="sort('ip')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Automatic')}} <a wire:click="sort('automatic')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Language')}} <a wire:click="sort('language')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Active links')}} <a wire:click="sort('active_links')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($sites))
                                @foreach($sites as $site)
                                    <tr>
                                        <td>
                                            {{ $site->name }}
                                            @if(intval($site->error) == 1)
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="{{__('The username or password is incorrect')}}">
                                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                                </a>
                                            @endif
                                            @if(intval($site->error) == 2)
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="{{__('API not found')}}">
                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td><a href="{{ $site->url }}" target="_blank" rel="nofollow">{{ $site->url }}</a></td>
                                        <td>{{ wordpress_type($site->type) }}</td>
                                        <td>{{ $site->ip }}</td>
                                        <td>@if($site->automatic) <i class="fas fa-check-circle text-success"></i> @else <i class="fas fa-times-circle text-danger"></i> @endif</td>
                                        <td>{{ @$site->languages ? @$site->languages->description : '' }}</td>
                                        <td>{{ $site->active_links }}</td>
                                        <td>
                                            @if(permission('wordpress', 'update'))
                                                <a class="blues" wire:click="modalEditCategories({{$site->id}})" alt="{{__('Category visibility')}}" title="{{__('Category visibility')}}"><span class="block"><i class="fas fa-cog"></i></span></a>
                                                <a class="blues" wire:click="modalEditSite({{$site->id}})" alt="{{__('Edit site')}}" title="{{__('Edit site')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                            @endif
                                            @if(permission('wordpress', 'delete'))
                                                <a class="reds" wire:click="confirm({{$site->id}}, {{$site->active_links}})" alt="{{__('Delete site')}}" title="{{__('Delete site')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">
                                        <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have wordpress sites added yet')}}</em></div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        @if(!empty($sites))
                            {{ $sites->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="addSite, editSite, editCategories, sort, delete">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="addSite" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create wordpress site')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successSite'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successSite') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="name" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="url" class="form-label">{{__('URL')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="url" type="url" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('url') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-label">{{__('Type')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="type" id="type" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    <option value="article">{{__('Article link')}}</option>
                                    <option value="sidebar">{{__('Sidebar link')}}</option>
                                    <option value="both">{{__('Article link')}} + {{__('Sidebar link')}}</option>
                                </select>
                                @error('type') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="ip" class="form-label">{{__('IP')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="ip" type="text" class="form-control" maxlength="45" :errors="$errors" autocomplete="off" />
                                @error('ip') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="username" class="form-label">{{__('Username')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="username" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('username') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">{{__('Password')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="password" type="password" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('password') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="language" class="form-label">{{__('Language')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="language" id="language" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($languages))
                                        @foreach($languages as $language)
                                            <option value="{{ $language->id }}">{{ $language->description }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('language') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <div class="form-group" wire:ignore>
                                    {{-- <label class="select2-categories">{{__('Categories for the homepage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label> --}}
                                    <div class="w-100 d-flex justify-content-between">
                                        <label for="category" class="form-label">{{__('Categories for the homepage')}}</label>
                                        <div>
                                            <input class="mr-1" type="checkbox" id="selectAllCategories" >{{ __('Select All')}}
                                        </div>
                                    </div>
                                    <select wire:model="category" class="form-control" multiple="multiple" id="select2-categories" :errors="$errors" autocomplete="off">
                                        @if(!empty($categories))
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{-- New Section --}}

                            <div class="form-group" wire:ignore>
                                <div class="w-100 d-flex justify-content-between">
                                    <label for="subcategory" class="form-label">{{__('Allowed users ')}}</label>
                                </div>
                                <select wire:model="users_selected" class="form-control" multiple="multiple" id="select2-users" :errors="$errors" autocomplete="off">
                                    @if(!empty($users))
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name." ".$user->lastname }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- END New Section --}}
                            
                            <div class="form-check">
                                <input class="form-check-input mt-1" type="checkbox" id="automatic" wire:model.lazy="automatic">
                                <label class="form-check-label" for="automatic">
                                    {{__('Automatic links')}}
                                </label>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addSite">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addSite">
                            <button type="button" wire:click="addSite" class="btn btn-primary">{{__('Save site')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editSite" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit site')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successSite'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successSite') }}
                                </div>
                            @endif
                            <input type="hidden" wire:model="site_id" />
                            <div class="form-group">
                                <label for="name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="name" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="url" class="form-label">{{__('URL')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="url" type="url" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('url') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="type_edit" class="form-label">{{__('Type')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="type" id="type_edit" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    <option value="article">{{__('Article link')}}</option>
                                    <option value="sidebar">{{__('Sidebar link')}}</option>
                                    <option value="both">{{__('Article link')}} + {{__('Sidebar link')}}</option>
                                </select>
                                @error('type') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="ip" class="form-label">{{__('IP')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="ip" type="text" class="form-control" maxlength="45" :errors="$errors" autocomplete="off" />
                                @error('ip') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="username" class="form-label">{{__('Username')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="username" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('username') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">{{__('Password')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="password" type="password" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('password') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="language_edit" class="form-label">{{__('Language')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="language" id="language_edit" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($languages))
                                        @foreach($languages as $language)
                                            <option value="{{ $language->id }}">{{ $language->description }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('language') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <div class="form-group" wire:ignore>

                                    <div class="w-100 d-flex justify-content-between">
                                        <label for="select2-edit-categories" class="form-label">{{__('Categories for the homepage')}}</label>
                                        <input wire:model.lazy="categories_id" type="hidden" />
                                        <div>
                                            <input class="mr-1" type="checkbox" id="selectEditAllCategories" >{{ __('Select All')}}
                                        </div>
                                    </div>
                                    
                                    {{-- <label for="select2-edit-categories" class="form-label" wire:ignore>{{__('Categories for the homepage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model.lazy="categories_id" type="hidden" /> --}}
                                    <select name="select2-edit-categories" wire:model="category" class="form-control" multiple="multiple" id="select2-edit-categories" :errors="$errors" autocomplete="off">
                                        @if(!empty($categories))
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" wire:ignore>
                                <div class="w-100 d-flex justify-content-between">
                                    <label for="users" class="form-label">{{__('Allowed users')}}</label>
                                    <div>
                                        <input class="mr-1" type="checkbox" id="selectEditAllUsers" >{{ __('Select All')}}
                                    </div>
                                </div>
                                <select wire:model="users_selected" class="form-control" multiple="multiple" id="select2-edit-users" :errors="$errors" autocomplete="off">
                                    @if(!empty($users))
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name." ".$user->lastname }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="automatic" wire:model.lazy="automatic">
                                <label class="form-check-label" for="automatic">
                                    {{__('Automatic links')}}
                                </label>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editSite">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editSite">
                            <button type="button" wire:click="editSite" class="btn btn-primary">{{__('Edit site')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editCategories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Category visibility')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form pt-4">
                            @if(session()->has('successCategories'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successCategories') }}
                                </div>
                            @endif

                            @if(!empty($list))
                                @if(count($list) > 0)
                                    @foreach($list as $item)
                                        <div class="form-group mt-2">
                                            <div class="row">
                                                <div class="col-3 text-right">
                                                    <label for="category_{{ $item->url }}" class="form-label">{{ $item->name }}</label>
                                                </div>
                                                <div class="col-9 text-left">
                                                    <select wire:model="selections.{{ $item->id }}" wire:change="change_selections" class="form-control" :errors="$errors" autocomplete="off">
                                                        <option value="0">{{__('Not visible')}}</option>
                                                        <option value="1">{{__('Visible, but hidden if it doesn\'t exist')}}</option>
                                                        <option value="2">{{__('Always visible, even if it\'s empty')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="form-group my-3 text-center text-muted">
                                        <p>{{__('The site does not contain categories yet')}}</p>
                                    </div>
                                @endif
                            @else
                                <div class="form-group my-3 text-center text-muted">
                                    <p>{{__('The site does not contain categories yet')}}</p>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="modal-footer">
                        @if(!empty($list) and count($list) > 0)
                            <div wire:loading wire:target="editCategories">
                                <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                            </div>
                            <div wire:loading.remove wire:target="editCategories">
                                <button type="button" wire:click="editCategories" class="btn btn-primary">{{__('Edit categories')}}</button>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                        @else
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('OK')}}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Confirm delete')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p>{{__('Are you sure want to delete this wordpress site?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="delete" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Warning')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p>{{__('You cannot remove sites that have active links')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger close-modal" data-dismiss="modal">{{__('OK')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>

        $("#selectAllCategories").click(function(){
            if($("#selectAllCategories").is(':checked') ){
                $("#select2-categories > option").prop("selected","selected");
                $("#select2-categories").trigger("change");
            }else{
                $('select#select2-categories').val(null).trigger('change');
            }
        });

        $("#selectEditAllCategories").click(function(){
            if($("#selectEditAllCategories").is(':checked') ){
                $("#select2-edit-categories > option").prop("selected","selected");
                $("#select2-edit-categories").trigger("change");
            }else{
                $('select#select2-edit-categories').val(null).trigger('change');
            }
        });

        document.addEventListener('livewire:load', function () {
            $('#select2-users').select2();
            $('#select2-edit-users').select2();

            $('#select2-categories').select2();
            $('#select2-edit-categories').select2();

            $('#select2-users').on('change', function (e) {
                let data = ($('#select2-users').select2("val"));
                @this.set('users_selected', data);
            });

            $('#select2-edit-users').on('change', function (e) {
                let data = ($('#select2-edit-users').select2("val"));
                @this.set('users_selected', data);
            });

            $('#select2-categories').on('change', function (e) {
                let data = ($('#select2-categories').select2("val"));
                @this.set('category', data);
            });

            $('#select2-edit-categories').on('change', function (e) {
                let data = ($('#select2-edit-categories').select2("val"));
                @this.set('category', data);
            });
        });

        window.addEventListener('resetCategories', event => {
            $('#select2-categories').empty();
            $('#select2-categories').select2({data: event.detail.options, width: '100%'});
        });

        window.addEventListener('resetEditCategories', event => {
            $('#select2-edit-categories').empty();
            $('#select2-edit-categories').select2({data: event.detail.options, width: '100%'});
        });

        window.addEventListener('resetEditUsers', event => {
            $('#select2-edit-users').val(@this.get('users_selected')).trigger('change');
        });

        window.addEventListener('showAddSite', event => {
            $('select#select2-categories').val(null).trigger('change');
            //$('#addSite').modal('show');
            $('#addSite').modal({
                backdrop: 'static', 
                keyboard: false
            });
            $('#select2-categories').empty();
            $('#select2-categories').select2({data: null, width: '100%'});

            $('#select2-users').empty();
            $('#select2-users').select2({data: null, width: '100%'});
        });

        window.addEventListener('hideAddSite', event => {
            $('#addSite').modal('hide');
        });

        window.addEventListener('showEditSite', event => {
            //$('#editSite').modal('show');
            $('#editSite').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideEditSite', event => {
            $('#editSite').modal('hide');
        });

        window.addEventListener('showEditCategories', event => {
            //$('#editCategories').modal('show');
            $('#editCategories').modal({
                backdrop: 'static', 
                keyboard: false
            });
            $('#select2-edit-categories').select2();
            $('#select2-edit-users').select2();
        });

        window.addEventListener('hideEditCategories', event => {
            $('#editCategories').modal('hide');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('warningDelete', event => {
            $('#warningModal').modal('show');
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
