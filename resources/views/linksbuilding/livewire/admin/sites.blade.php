@section('title')
    {{ $title }}
@endsection
@dump("here")
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
                @if(permission('sites', 'create'))
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
                                <th>{{__('Currency')}} <a wire:click="sort('currency')"><i class="fas fa-sort"></i></a></th>
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
                                        <td>@if(!empty($site->logo) and File::exists(public_path($site->logo)))<img src="{{ asset($site->logo) }}" class="thumb-image mr-1" width="50" height="auto" />@endif {{ $site->name }}</td>
                                        <td><a href="{{ $site->url }}" target="_blank" rel="nofollow">{{ $site->url }}</a></td>
                                        <td>{{ $site->type }}</td>
                                        <td>{{ $site->currency }}</td>
                                        <td>@if($site->automatic) <i class="fas fa-check-circle text-success"></i> @else <i class="fas fa-times-circle text-danger"></i> @endif</td>
                                        <td>{{ $site->languages ? $site->languages->description : '' }}</td>
                                        <td>{{ $site->active_links }}</td>
                                        <td>
                                            @if(permission('sites', 'update'))
                                                <a class="blues" wire:click="modalEditCategories({{$site->id}})" alt="{{__('Category visibility')}}" title="{{__('Category visibility')}}"><span class="block"><i class="fas fa-cog"></i></span></a>
                                                <a class="blues" wire:click="modalEditSite({{$site->id}})" alt="{{__('Edit site')}}" title="{{__('Edit site')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                            @endif
                                            @if(permission('sites', 'delete'))
                                                @if($site->active_links == 0)
                                                    <a class="reds" wire:click="confirm({{$site->id}}, {{$site->active_links}})" alt="{{__('Delete site')}}" title="{{__('Delete site')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">
                                        <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have sites added yet')}}</em></div>
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
            <div class="modal-dialog modal-lg mw-700" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create site')}}</h4>
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
                                <label for="ip" class="form-label">{{__('IP')}}</label>
                                <input wire:model.lazy="ip" type="text" class="form-control" maxlength="45" :errors="$errors" autocomplete="off" />
                                @error('ip') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="logo">{{__('Logo')}}</label>
                                <input type="file" class="form-control-file @error('logo') is_error @enderror" id="logo" wire:model.lazy="logo" />
                                @if($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @error('logo')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-label">{{__('Type')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="type" id="type" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    <option value="Link building system">{{__('Link building system')}}</option>
                                    {{--<option value="{{__('Selling system')}}">{{__('Selling system')}}</option>--}}
                                </select>
                                @error('type') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="header" class="form-label">{{__('Header title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="header" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('header') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="row">
                                <div class="col mb-3" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="menu" class="form-label">{{__('Menu color')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <div id="menu" class="input-group" title="{{__('Menu color')}}" wire:ignore>
                                        <input name="menu" type="text" class="form-control input-lg" autocomplete="off" />
                                        <span class="input-group-append">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                    </div>
                                    @error('menu') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col mb-3" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="links" class="form-label">{{__('Links color')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <div id="links" class="input-group" title="{{__('Links color')}}" wire:ignore>
                                        <input name="links" type="text" class="form-control input-lg" autocomplete="off" />
                                        <span class="input-group-append">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                    </div>
                                    @error('links') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col mb-3" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="box" class="form-label">{{__('Box color')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <div id="box" class="input-group" title="{{__('Box color')}}" wire:ignore>
                                        <input name="box" type="text" class="form-control input-lg" autocomplete="off" />
                                        <span class="input-group-append">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                    </div>
                                    @error('box') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Footer column 1')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" id="quill" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor"
                                         x-init="quill = new Quill($refs.quillEditor, {theme: 'snow'});
                                            quill.on('text-change', function () {
                                                $dispatch('input', quill.root.innerHTML);
                                                @this.set('footer', quill.root.innerHTML)
                                            });"
                                         wire:model.lazy="footer">
                                        {!! $footer !!}
                                    </div>
                                </div>
                                @error('footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer2" class="form-label">{{__('Footer column 2')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" id="quill2" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor2"
                                         x-init="quill2 = new Quill($refs.quillEditor2, {theme: 'snow'});
                                        quill2.on('text-change', function () {
                                            $dispatch('input', quill2.root.innerHTML);
                                            @this.set('footer2', quill2.root.innerHTML)
                                        });"
                                         wire:model.lazy="footer2">
                                        {!! $footer2 !!}
                                    </div>
                                </div>
                                @error('footer2')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer3" class="form-label">{{__('Footer column 3')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" id="quill3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor3"
                                         x-init="quill3 = new Quill($refs.quillEditor3, {theme: 'snow'});
                                        quill3.on('text-change', function () {
                                            $dispatch('input', quill3.root.innerHTML);
                                            @this.set('footer3', quill3.root.innerHTML)
                                        });"
                                         wire:model.lazy="footer3">
                                        {!! $footer3 !!}
                                    </div>
                                </div>
                                @error('footer3')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="contact" class="form-label">{{__('Email contact')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="contact" type="text" maxlength="255" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('contact') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="currency" class="form-label">{{__('Currency')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="currency" id="currency" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($currencies))
                                        @foreach($currencies as $i => $currency)
                                            <option value="{{ $i }}">{{ $currency }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('currency') <span class="error">{{ $message }}</span> @enderror
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
                            <div class="form-group" wire:ignore>
                                <label for="category" class="form-label">{{__('Categories for the homepage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="category" class="form-control" multiple="multiple" id="select2-categories" :errors="$errors" autocomplete="off">
                                    @if(!empty($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group" wire:ignore>
                                <label for="subcategory" class="form-label">{{__('Daughters pages')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="subcategory" class="form-control" multiple="multiple" id="select2-subcategories" :errors="$errors" autocomplete="off">
                                    @if(!empty($subcategories))
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
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
            <div class="modal-dialog modal-lg mw-700" role="document">
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
                                <label for="ip" class="form-label">{{__('IP')}}</label>
                                <input wire:model.lazy="ip" type="text" class="form-control" maxlength="45" :errors="$errors" autocomplete="off" />
                                @error('ip') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="logo">{{__('Logo')}}</label>
                                <input type="file" class="form-control-file @error('logo') is_error @enderror" id="logo" wire:model.lazy="logo" />
                                @if($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @if($preview)
                                    <img src="{{ $preview }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @error('logo')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-label">{{__('Type')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="type" id="type" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    <option value="Link building system">{{__('Link building system')}}</option>
                                    {{--<option value="{{__('Selling system')}}">{{__('Selling system')}}</option>--}}
                                </select>
                                @error('type') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="header" class="form-label">{{__('Header title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="header" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('header') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="row">
                                <div class="col mb-3" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="menu_edit" class="form-label">{{__('Menu color')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <div id="menu_edit" class="input-group" title="{{__('Menu color')}}" wire:ignore>
                                        <input name="menu" type="text" class="form-control input-lg" autocomplete="off" />
                                        <span class="input-group-append">
                                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                    </span>
                                    </div>
                                    @error('menu') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col mb-3" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="links_edit" class="form-label">{{__('Links color')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <div id="links_edit" class="input-group" title="{{__('Links color')}}" wire:ignore>
                                        <input name="links" type="text" class="form-control input-lg" autocomplete="off" />
                                        <span class="input-group-append">
                                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                    </span>
                                    </div>
                                    @error('links') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col mb-3" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="box_edit" class="form-label">{{__('Box color')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <div id="box_edit" class="input-group" title="{{__('Box color')}}" wire:ignore>
                                        <input name="box" type="text" class="form-control input-lg" autocomplete="off" />
                                        <span class="input-group-append">
                                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                    </span>
                                    </div>
                                    @error('box') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Footer column 1')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" id="quill_edit" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor"
                                         x-init="
                                     quill_data = '{{ $footer }}';
                                     quill_edit = new Quill($refs.quillEditor, {theme: 'snow'});
                                     quill_edit.on('text-change', function () {
                                       $dispatch('input', quill_edit.root.innerHTML);
                                       @this.set('footer', quill_edit.root.innerHTML)
                                     });
                                "
                                         wire:model.lazy="footer"
                                    >
                                        {!! $footer !!}
                                    </div>
                                </div>
                                @error('footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer2" class="form-label">{{__('Footer column 2')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" id="quill_edit2" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor2"
                                         x-init="
                                             quill_data2 = '{{ $footer2 }}';
                                             quill_edit2 = new Quill($refs.quillEditor2, {theme: 'snow'});
                                             quill_edit2.on('text-change', function () {
                                               $dispatch('input', quill_edit2.root.innerHTML);
                                               @this.set('footer2', quill_edit2.root.innerHTML)
                                             });
                                        "
                                         wire:model.lazy="footer2"
                                    >
                                        {!! $footer2 !!}
                                    </div>
                                </div>
                                @error('footer2')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer3" class="form-label">{{__('Footer column 3')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" id="quill_edit3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor3"
                                         x-init="
                                             quill_data3 = '{{ $footer3 }}';
                                             quill_edit3 = new Quill($refs.quillEditor3, {theme: 'snow'});
                                             quill_edit3.on('text-change', function () {
                                               $dispatch('input', quill_edit3.root.innerHTML);
                                               @this.set('footer3', quill_edit3.root.innerHTML)
                                             });
                                        "
                                         wire:model.lazy="footer3"
                                    >
                                        {!! $footer3 !!}
                                    </div>
                                </div>
                                @error('footer3')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="contact" class="form-label">{{__('Email contact')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="contact" type="text" maxlength="255" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('contact') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="currency" class="form-label">{{__('Currency')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="currency" id="currency" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($currencies))
                                        @foreach($currencies as $i => $currency)
                                            <option value="{{ $i }}">{{ $currency }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('currency') <span class="error">{{ $message }}</span> @enderror
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
                            <div class="form-group" wire:ignore>
                                <label for="category" class="form-label">{{__('Categories for the homepage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="categories_id" type="hidden" />
                                <select wire:model="category" class="form-control" multiple="multiple" id="select2-edit-categories" :errors="$errors" autocomplete="off">
                                    @if(!empty($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group" wire:ignore>
                                <label for="subcategory" class="form-label">{{__('Daughters pages')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="subcategories_id" type="hidden" />
                                <select wire:model="subcategory" class="form-control" multiple="multiple" id="select2-edit-subcategories" :errors="$errors" autocomplete="off">
                                    @if(!empty($subcategories))
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
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
                        <p>{{__('Are you sure want to delete this site?')}}</p>
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
        document.addEventListener('livewire:load', function () {
            $('#select2-categories').select2();
            $('#select2-subcategories').select2();
            $('#select2-edit-categories').select2();
            $('#select2-edit-subcategories').select2();

            $('#select2-categories').on('change', function (e) {
                let data = ($('#select2-categories').select2("val"));
                @this.set('category', data);
            });

            $('#select2-subcategories').on('change', function (e) {
                let data = ($('#select2-subcategories').select2("val"));
                @this.set('subcategory', data);
            });

            $('#select2-edit-categories').on('change', function (e) {
                let data = ($('#select2-edit-categories').select2("val"));
                @this.set('category', data);
            });

            $('#select2-edit-subcategories').on('change', function (e) {
                let data = ($('#select2-edit-subcategories').select2("val"));
                @this.set('subcategory', data);
            });

            $('#menu, #links, #box').colorpicker();
            $('#menu_edit, #links_edit, #box_edit').colorpicker();

            $('#menu, #menu_edit').on('colorpickerChange', function(event) {
                @this.set('menu', event.color.toString());
            });
            $('#links, #links_edit').on('colorpickerChange', function(event) {
                @this.set('links', event.color.toString());
            });
            $('#box, #box_edit').on('colorpickerChange', function(event) {
                @this.set('box', event.color.toString());
            });
        });

        window.addEventListener('resetCategories', event => {
            $('#select2-categories').empty();
            $('#select2-categories').select2({data: event.detail.options, width: '100%'});
        });

        window.addEventListener('resetSubcategories', event => {
            $('#select2-subcategories').empty();
            $('#select2-subcategories').select2({data: event.detail.options, width: '100%'});
        });

        window.addEventListener('resetEditCategories', event => {
            $('#select2-edit-categories').empty();
            $('#select2-edit-categories').select2({data: event.detail.options, width: '100%'});
        });

        window.addEventListener('resetEditSubcategories', event => {
            $('#select2-edit-subcategories').empty();
            $('#select2-edit-subcategories').select2({data: event.detail.options, width: '100%'});
        });

        window.addEventListener('resetPicker', event => {
            $('#menu_edit').colorpicker('setValue', event.detail.menu);
            $('#links_edit').colorpicker('setValue', event.detail.links);
            $('#box_edit').colorpicker('setValue', event.detail.box);
        });

        window.addEventListener('showAddSite', event => {
            $('#logo').val(null);
            $('select#select2-categories').val(null).trigger('change');
            $('select#select2-subcategories').val(null).trigger('change');
            quill.container.firstChild.innerHTML  = '';
            quill2.container.firstChild.innerHTML = '';
            quill3.container.firstChild.innerHTML = '';
            $('#addSite').modal('show');
        });

        window.addEventListener('hideAddSite', event => {
            $('#addSite').modal('hide');
        });

        window.addEventListener('showEditSite', event => {
            quill_edit.setContents(quill_edit.clipboard.convert(event.detail.editor), 'silent');
            quill_edit2.setContents(quill_edit2.clipboard.convert(event.detail.footer2), 'silent');
            quill_edit3.setContents(quill_edit3.clipboard.convert(event.detail.footer3), 'silent');
            $('#editSite').modal('show');
        });

        window.addEventListener('showEditCategories', event => {
            $('#editCategories').modal('show');
        });

        window.addEventListener('hideEditCategories', event => {
            $('#editCategories').modal('hide');
        });

        window.addEventListener('hideEditSite', event => {
            $('#editSite').modal('hide');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('warningDelete', event => {
            $('#warningModal').modal('show');
        });
    </script>
@endpush
