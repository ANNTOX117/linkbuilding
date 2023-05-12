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
                @if(permission('sites', 'create'))
                    <a data-toggle="modal" wire:click="modalAddSite" data-backdrop="static" data-keyboard="false"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add site')}}</span></a>
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
                                                <a class="blues" wire:click="modalEditTextSite({{$site->id}})" alt="{{__('Edit texts site')}}" title="{{__('Edit texts site')}}"><span class="block"><i class="far fa-list-alt"></i></span></a>
                                                <a class="blues" wire:click="modalExtraSeetings({{$site->id}})" alt="{{__('Add or edit extra settings')}}" title="{{__('Add or edit extra settings')}}"><span class="block"><i class="fas fa-cogs"></i></span></a>
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
                                <label for="title" class="form-label">{{__('Meta title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="meta_title" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('meta_title') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{__('Meta description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="meta_description" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('meta_description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="ip" class="form-label">{{__('IP')}}</label>
                                <input wire:model.lazy="ip" type="text" class="form-control" maxlength="45" :errors="$errors" autocomplete="off" />
                                @error('ip') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-label">{{__('Type')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="type" id="type" class="form-control" :errors="$errors">
                                    <option value="Link building system">{{__('Link building system')}}</option>
                                    <option value="Blog page">{{__('Blog page')}}</option>
                                    {{--<option value="{{__('Selling system')}}">{{__('Selling system')}}</option>--}}
                                </select>
                                @error('type') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="logo">{{__('Logo')}}</label>
                                <input type="file" class="form-control-file @error('logo') is_error @enderror" id="logo" wire:model.lazy="logo" />
                                @if($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @error('logo')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="slider_background">{{__('Slider background')}}</label>
                                <input type="file" class="form-control-file @error('slider_background') is_error @enderror" id="slider_background" wire:model.lazy="slider_background" />
                                @if($slider_background)
                                    <img src="{{ $slider_background->temporaryUrl() }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @error('slider_background')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="siteImageHeader">{{__('Slider image header')}}</label>
                                <input type="file" class="form-control-file @error('siteImageHeader') is_error @enderror" id="siteImageHeader" wire:model.lazy="siteImageHeader" />
                                @if($siteImageHeader)
                                    <img src="{{ $siteImageHeader->temporaryUrl() }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @error('siteImageHeader')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="siteImageProvince">{{__('Slider image category provience')}}</label>
                                <input type="file" class="form-control-file @error('siteImageProvince') is_error @enderror" id="siteImageProvince" wire:model.lazy="siteImageProvince" />
                                @if($siteImageProvince)
                                    <img src="{{ $siteImageProvince->temporaryUrl() }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @error('siteImageProvince')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="number_slides" class="form-label">{{__('Number of slides')}}</label>
                                <input wire:model.lazy="number_slides" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('number_slides') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            @foreach ($slide_inputs as $key => $input )
                                <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <h5>Slide # {{$key+1}}</h5>
                                </div>
                                <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="slide_header_{{$key}}" class="form-label">{{__('Slide header')}}</label>
                                    <input wire:model.lazy="slide_inputs.{{$key}}.slide_header" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    {{-- @error('slide_inputs'.{{$key}}.'slide_header') <span class="error">{{ $message }}</span> @enderror --}}
                                </div>
                                <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="slide_description_{{$key}}" class="form-label">{{__('Slide description')}}</label>
                                    <input wire:model.lazy="slide_inputs.{{$key}}.slide_description" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    {{-- @error('slide_inputs'.{{$key}}.'slide_description') <span class="error">{{ $message }}</span> @enderror --}}
                                </div>
                                <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="slide_link_{{$key}}" class="form-label">{{__('Slide link URL')}}</label>
                                    <input wire:model.lazy="slide_inputs.{{$key}}.slide_link" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    {{-- @error('slide_inputs'.{{$key}}.'slide_link') <span class="error">{{ $message }}</span> @enderror --}}
                                </div>
                                <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="slide_anchor_{{$key}}" class="form-label">{{__('Slide link anchor')}}</label>
                                    <input wire:model.lazy="slide_inputs.{{$key}}.slide_anchor" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    {{-- @error('slide_inputs'.{{$key}}.'slide_anchor') <span class="error">{{ $message }}</span> @enderror --}}
                                </div>
                            @endforeach
                            {{-- <div class="form-group mb-0">
                                <label for="slide_header" class="form-label">{{__('Slide header')}}</label>
                                <input wire:model.lazy="slide_header" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('slide_header') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label for="slide_description" class="form-label">{{__('Slide description')}}</label>
                                <input wire:model.lazy="slide_description" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('slide_description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label for="slide_link" class="form-label">{{__('Slide link')}}</label>
                                <input wire:model.lazy="slide_link" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('slide_link') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="slide_anchor" class="form-label">{{__('Slide anchor')}}</label>
                                <input wire:model.lazy="slide_anchor" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('slide_anchor') <span class="error">{{ $message }}</span> @enderror
                            </div> --}}
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="header" class="form-label">{{__('Header title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="header" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('header') <span class="error">{{ $message }}</span> @enderror
                            </div>

                            {{-- <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Header text')}} </label>
                                <div class="mb-3" id="quill4" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor4"
                                         x-init="quill4 = new Quill($refs.quillEditor4, {theme: 'snow'});
                                            quill4.on('text-change', function () {
                                                $dispatch('input', quill4.root.innerHTML);
                                                @this.set('headerText', quill4.root.innerHTML)
                                            });"
                                         wire:model.lazy="headerText">
                                        {!! $headerText !!}
                                    </div>
                                </div>
                                @error('headerText')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Footer text')}} </label>
                                <div class="mb-3" id="quill5" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor5"
                                         x-init="quill5 = new Quill($refs.quillEditor5, {theme: 'snow'});
                                         quill5.on('text-change', function () {
                                                $dispatch('input', quill5.root.innerHTML);
                                                @this.set('footerText', quill5.root.innerHTML)
                                            });"
                                         wire:model.lazy="footerText">
                                        {!! $footerText !!}
                                    </div>
                                </div>
                                @error('footerText')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Blog header')}} </label>
                                <div class="mb-3" id="quill6" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor6"
                                         x-init="quill6 = new Quill($refs.quillEditor6, {theme: 'snow'});
                                            quill6.on('text-change', function () {
                                                $dispatch('input', quill6.root.innerHTML);
                                                @this.set('blog_header', quill6.root.innerHTML)
                                            });"
                                         wire:model.lazy="blog_header">
                                        {!! $blog_header !!}
                                    </div>
                                </div>
                                @error('blog_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Blog footer')}} </label>
                                <div class="mb-3" id="quill7" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor7"
                                         x-init="quill7 = new Quill($refs.quillEditor7, {theme: 'snow'});
                                         quill7.on('text-change', function () {
                                                $dispatch('input', quill7.root.innerHTML);
                                                @this.set('blog_footer', quill7.root.innerHTML)
                                            });"
                                         wire:model.lazy="blog_footer">
                                        {!! $blog_footer !!}
                                    </div>
                                </div>
                                @error('blog_footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Categories header')}} </label>
                                <div class="mb-3" id="quill8" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor8"
                                         x-init="quill8 = new Quill($refs.quillEditor8, {theme: 'snow'});
                                            quill8.on('text-change', function () {
                                                $dispatch('input', quill8.root.innerHTML);
                                                @this.set('daughter_header', quill8.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_header">
                                        {!! $daughter_header !!}
                                    </div>
                                </div>
                                @error('daughter_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Categories footer')}} </label>
                                <div class="mb-3" id="quill9" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor9"
                                         x-init="quill9 = new Quill($refs.quillEditor9, {theme: 'snow'});
                                         quill9.on('text-change', function () {
                                                $dispatch('input', quill9.root.innerHTML);
                                                @this.set('daughter_footer', quill9.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_footer">
                                        {!! $daughter_footer !!}
                                    </div>
                                </div>
                                @error('daughter_footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Daughter header')}} </label>
                                <div class="mb-3" id="quill11" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor11"
                                         x-init="quill11 = new Quill($refs.quillEditor11, {theme: 'snow'});
                                         quill11.on('text-change', function () {
                                                $dispatch('input', quill11.root.innerHTML);
                                                @this.set('daughter_home_header', quill10.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_home_header">
                                        {!! $daughter_home_header !!}
                                    </div>
                                </div>
                                @error('daughter_home_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Daughter footer')}} </label>
                                <div class="mb-3" id="quill12" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor12"
                                         x-init="quill12 = new Quill($refs.quillEditor12, {theme: 'snow'});
                                         quill12.on('text-change', function () {
                                                $dispatch('input', quill12.root.innerHTML);
                                                @this.set('daughter_home_footer', quill12.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_home_footer">
                                        {!! $daughter_home_footer !!}
                                    </div>
                                </div>
                                @error('daughter_home_footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Daughter blog header')}} </label>
                                <div class="mb-3" id="quill13" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor13"
                                         x-init="quill13 = new Quill($refs.quillEditor13, {theme: 'snow'});
                                         quill13.on('text-change', function () {
                                                $dispatch('input', quill13.root.innerHTML);
                                                @this.set('daughter_blog_header', quill13.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_blog_header">
                                        {!! $daughter_blog_header !!}
                                    </div>
                                </div>
                                @error('daughter_blog_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Daughter footer')}} </label>
                                <div class="mb-3" id="quill14" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor14"
                                         x-init="quill14 = new Quill($refs.quillEditor14, {theme: 'snow'});
                                         quill14.on('text-change', function () {
                                                $dispatch('input', quill14.root.innerHTML);
                                                @this.set('daughter_blog_footer', quill14.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_blog_footer">
                                        {!! $daughter_blog_footer !!}
                                    </div>
                                </div>
                                @error('daughter_blog_footer')<span class="error">{{ $message }}</span>@enderror
                            </div> --}}



                            {{-- @if (!empty($site_categories))
                                @foreach ($site_categories as $key => $input )
                                    <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                        <h5>{{ $input->name }} texts</h5>
                                    </div>
                                    <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                        <label for="category_header_{{$key}}" class="form-label">{{ $input->name }} header text</label>
                                        <input wire:model.lazy="category.{{$key}}.header_text" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    </div>
                                    <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                        <label for="category_footer_{{$key}}" class="form-label">{{ $input->name }} footer text</label>
                                        <input wire:model.lazy="category.{{$key}}.footer_text" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    </div>
                                @endforeach
                            @endif --}}





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
                                <label for="footer" class="form-label">{{__('Footer column 1')}} </label>
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
                                <label for="footer2" class="form-label">{{__('Footer column 2')}} </label>
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
                                <label for="footer3" class="form-label">{{__('Footer column 3')}} </label>
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
                                <label for="footer4" class="form-label">{{__('Footer column 4')}} </label>
                                <div class="mb-3" id="quill10" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor10"
                                         x-init="quill10 = new Quill($refs.quillEditor10, {theme: 'snow'});
                                        quill10.on('text-change', function () {
                                            $dispatch('input', quill10.root.innerHTML);
                                            @this.set('footer4', quill10.root.innerHTML)
                                        });"
                                         wire:model.lazy="footer4">
                                        {!! $footer4 !!}
                                    </div>
                                </div>
                                @error('footer4')<span class="error">{{ $message }}</span>@enderror
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

                                <div class="w-100 d-flex justify-content-between">
                                    <label for="category" class="form-label">{{__('Categories for the homepage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    {{-- <div>
                                        <input class="mr-1" type="checkbox" id="selectAllCategories" >{{ __('Select All')}}
                                    </div> --}}
                                </div>
                                <select wire:model="category" class="form-control" multiple="multiple" id="select2-categories" :errors="$errors" autocomplete="off">
                                    @if(!empty($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            

                            <div class="form-group" wire:ignore>
                                <div class="w-100 d-flex justify-content-between">
                                    <label for="subcategory" class="form-label">{{__('Daughters pages')}}</label>
                                    <div>
                                        <input class="mr-1" type="checkbox" id="selectAllSubCategories">{{ __('Select All')}}
                                    </div>
                                </div>
                                <select wire:model="subcategory" class="form-control" multiple="multiple" id="select2-subcategories" :errors="$errors" autocomplete="off">
                                    @if(!empty($subcategories))
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>


                            {{-- New Section --}}

                            <div class="form-group" wire:ignore>
                                
                                {{-- <div class="w-100 d-flex justify-content-between">
                                    <label for="subcategory" class="form-label">{{__('Allowed users ')}}</label>
                                </div> --}}

                                <div class="w-100 d-flex justify-content-between">
                                    <label for="users" class="form-label">{{__('Allowed users')}}</label>
                                    <div>
                                        <input class="mr-1" type="checkbox" id="selectAllUsers" >{{ __('Select All')}}
                                    </div>
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
                                <input class="form-check-input" type="checkbox" id="automatic" wire:model.lazy="automatic">
                                <label class="form-check-label" for="automatic">
                                    {{__('Automatic links')}}
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="permanent" wire:model.lazy="permanent">
                                <label class="form-check-label" for="permanent">
                                    {{__('Permanent link')}}
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="no_index_follow" wire:model.lazy="no_index_follow">
                                <label class="form-check-label" for="no_index_follow">
                                    {{__('No index follow')}}
                                </label>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <span class="text-muted">{{__('Required fields')}}</span></em></small>
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
                                <label for="title" class="form-label">{{__('Meta title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="meta_title" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('meta_title') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{__('Meta description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="meta_description" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('meta_description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="ip" class="form-label">{{__('IP')}}</label>
                                <input wire:model.lazy="ip" type="text" class="form-control" maxlength="45" :errors="$errors" autocomplete="off" />
                                @error('ip') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-label">{{__('Type')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="type" id="type" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    <option value="Link building system">{{__('Link building system')}}</option>
                                    <option value="Blog page">{{__('Blog page')}}</option>
                                    {{--<option value="{{__('Selling system')}}">{{__('Selling system')}}</option>--}}
                                </select>
                                @error('type') <span class="error">{{ $message }}</span> @enderror
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
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="slider_background">{{__('Slider background')}}</label>
                                <input type="file" class="form-control-file @error('slider_background') is_error @enderror" id="slider_background" wire:model.lazy="slider_background" />
                                @if($slider_background)
                                    <img src="{{ $slider_background->temporaryUrl() }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @if($preview)
                                    <img src="{{ $preview }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @error('slider_background')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="favicon">{{__('Favicon')}}</label>
                                <input type="file" class="form-control-file @error('favicon') is_error @enderror" id="favicon" wire:model.lazy="favicon" />
                                @if($favicon)
                                    <img src="{{ $favicon->temporaryUrl() }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @if($previewFavicon)
                                    <img src="{{ $previewFavicon }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @error('favicon')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="number_slides" class="form-label">{{__('Number of slides')}}</label>
                                <input wire:model.lazy="number_slides" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('number_slides') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            @foreach ($slide_inputs as $key => $input )
                                <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <h5>Slide # {{$key+1}}</h5>
                                </div>
                                <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="slide_header_{{$key}}" class="form-label">{{__('Slide header')}}</label>
                                    <input wire:model.lazy="slide_inputs.{{$key}}.slide_header" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    {{-- @error('slide_inputs'.{{$key}}.'slide_header') <span class="error">{{ $message }}</span> @enderror --}}
                                </div>
                                <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="slide_description_{{$key}}" class="form-label">{{__('Slide description')}}</label>
                                    <input wire:model.lazy="slide_inputs.{{$key}}.slide_description" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    {{-- @error('slide_inputs'.{{$key}}.'slide_description') <span class="error">{{ $message }}</span> @enderror --}}
                                </div>
                                <div class="form-group mb-0" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="slide_link_{{$key}}" class="form-label">{{__('Slide link URL')}}</label>
                                    <input wire:model.lazy="slide_inputs.{{$key}}.slide_link" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    {{-- @error('slide_inputs'.{{$key}}.'slide_link') <span class="error">{{ $message }}</span> @enderror --}}
                                </div>
                                <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                    <label for="slide_anchor_{{$key}}" class="form-label">{{__('Slide link anchor')}}</label>
                                    <input wire:model.lazy="slide_inputs.{{$key}}.slide_anchor" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    {{-- @error('slide_inputs'.{{$key}}.'slide_anchor') <span class="error">{{ $message }}</span> @enderror --}}
                                </div>
                            @endforeach
                            {{-- <div class="form-group mb-0">
                                <label for="slide_header" class="form-label">{{__('Slide header')}}</label>
                                <input wire:model.lazy="slide_header" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('slide_header') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label for="slide_description" class="form-label">{{__('Slide description')}}</label>
                                <input wire:model.lazy="slide_description" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('slide_description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label for="slide_link" class="form-label">{{__('Slide link')}}</label>
                                <input wire:model.lazy="slide_link" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('slide_link') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="slide_anchor" class="form-label">{{__('Slide anchor')}}</label>
                                <input wire:model.lazy="slide_anchor" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('slide_anchor') <span class="error">{{ $message }}</span> @enderror
                            </div> --}}
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="header" class="form-label">{{__('Header title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model.lazy="header" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('header') <span class="error">{{ $message }}</span> @enderror
                            </div>

                            {{-- <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="headerText" class="form-label">{{__('Header text')}} </label>
                                <div class="mb-3" id="quill_edit4" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor4"
                                         x-init="
                                     quill_data4 = '{{ $headerText }}';
                                     quill_edit4 = new Quill($refs.quillEditor4, {theme: 'snow'});
                                     quill_edit4.on('text-change', function () {
                                       $dispatch('input', quill_edit4.root.innerHTML);
                                       @this.set('headerText', quill_edit4.root.innerHTML)
                                     });
                                "
                                         wire:model.lazy="headerText"
                                    >
                                        {!! $headerText !!}
                                    </div>
                                </div>
                                @error('headerText')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footerText" class="form-label">{{__('Footer text')}} </label>
                                <div class="mb-3" id="quill_edit5" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor5"
                                         x-init="
                                     quill_data5 = '{{ $footerText }}';
                                     quill_edit5 = new Quill($refs.quillEditor5, {theme: 'snow'});
                                     quill_edit5.on('text-change', function () {
                                       $dispatch('input', quill_edit5.root.innerHTML);
                                       @this.set('footerText', quill_edit5.root.innerHTML)
                                     });
                                "
                                         wire:model.lazy="footerText"
                                    >
                                        {!! $footerText !!}
                                    </div>
                                </div>
                                @error('footerText')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Blog header')}} </label>
                                <div class="mb-3" id="quill_edit6" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor6"
                                         x-init="
                                         quill_data6 = '{{ $blog_header }}';
                                         quill_edit6 = new Quill($refs.quillEditor6, {theme: 'snow'});
                                            quill_edit6.on('text-change', function () {
                                                $dispatch('input', quill_edit6.root.innerHTML);
                                                @this.set('blog_header', quill_edit6.root.innerHTML)
                                            });"
                                         wire:model.lazy="blog_header">
                                        {!! $blog_header !!}
                                    </div>
                                </div>
                                @error('blog_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Blog footer')}} </label>
                                <div class="mb-3" id="quill_edit7" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor7"
                                         x-init="
                                         quill_data7 = '{{ $blog_footer }}';
                                         quill_edit7 = new Quill($refs.quillEditor7, {theme: 'snow'});
                                         quill_edit7.on('text-change', function () {
                                                $dispatch('input', quill_edit7.root.innerHTML);
                                                @this.set('blog_footer', quill_edit7.root.innerHTML)
                                            });"
                                         wire:model.lazy="blog_footer">
                                        {!! $blog_footer !!}
                                    </div>
                                </div>
                                @error('blog_footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Categories header')}} </label>
                                <div class="mb-3" id="quill_edit8" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor8"
                                         x-init="
                                         quill_data8 = '{{ $daughter_header }}';
                                         quill_edit8 = new Quill($refs.quillEditor8, {theme: 'snow'});
                                         quill_edit8.on('text-change', function () {
                                                $dispatch('input', quill_edit8.root.innerHTML);
                                                @this.set('daughter_header', quill_edit8.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_header">
                                        {!! $daughter_header !!}
                                    </div>
                                </div>
                                @error('daughter_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Categories footer')}} </label>
                                <div class="mb-3" id="quill_edit9" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor9"
                                         x-init="
                                         quill_data9 = '{{ $daughter_footer }}';
                                         quill_edit9 = new Quill($refs.quillEditor9, {theme: 'snow'});
                                         quill_edit9.on('text-change', function () {
                                                $dispatch('input', quill_edit9.root.innerHTML);
                                                @this.set('daughter_footer', quill_edit9.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_footer">
                                        {!! $daughter_footer !!}
                                    </div>
                                </div>
                                @error('daughter_footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Daughter header')}} </label>
                                <div class="mb-3" id="quill_edit11" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor11"
                                         x-init="
                                         quill_data11 = '{{ $daughter_home_header }}';
                                         quill_edit11 = new Quill($refs.quillEditor11, {theme: 'snow'});
                                         quill_edit11.on('text-change', function () {
                                                $dispatch('input', quill_edit11.root.innerHTML);
                                                @this.set('daughter_home_header', quill_edit11.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_home_header">
                                        {!! $daughter_home_header !!}
                                    </div>
                                </div>
                                @error('daughter_home_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Daughter footer')}} </label>
                                <div class="mb-3" id="quill_edit12" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor12"
                                         x-init="
                                         quill_data12 = '{{ $daughter_home_footer }}';
                                         quill_edit12 = new Quill($refs.quillEditor12, {theme: 'snow'});
                                         quill_edit12.on('text-change', function () {
                                                $dispatch('input', quill_edit12.root.innerHTML);
                                                @this.set('daughter_home_footer', quill_edit12.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_home_footer">
                                        {!! $daughter_home_footer !!}
                                    </div>
                                </div>
                                @error('daughter_home_footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Daughter blog header')}} </label>
                                <div class="mb-3" id="quill_edit13" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor13"
                                         x-init="
                                         quill_data13 = '{{ $daughter_blog_header }}';
                                         quill_edit13 = new Quill($refs.quillEditor13, {theme: 'snow'});
                                         quill_edit13.on('text-change', function () {
                                                $dispatch('input', quill_edit13.root.innerHTML);
                                                @this.set('daughter_blog_header', quill_edit13.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_blog_header">
                                        {!! $daughter_blog_header !!}
                                    </div>
                                </div>
                                @error('daughter_blog_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Daughter blog footer')}} </label>
                                <div class="mb-3" id="quill_edit14" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor14"
                                         x-init="
                                         quill_data14 = '{{ $daughter_blog_footer }}';
                                         quill_edit14 = new Quill($refs.quillEditor14, {theme: 'snow'});
                                         quill_edit14.on('text-change', function () {
                                                $dispatch('input', quill_edit14.root.innerHTML);
                                                @this.set('daughter_blog_footer', quill_edit14.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_blog_footer">
                                        {!! $daughter_blog_footer !!}
                                    </div>
                                </div>
                                @error('daughter_blog_footer')<span class="error">{{ $message }}</span>@enderror
                            </div> --}}

                            {{-- @if (!empty($site_categories_array))
                                <hr>
                                @foreach ($site_categories_array as $key => $category_site )
                                    <div class="form-group mb-0 mt-2" @if($extras) style="display: block" @else style="display: none" @endif>
                                        <h5>CATEGORY: {{ strToUpper($category_site['category_url']) }}</h5>
                                    </div>
                                    <div class="form-group mb-0 mt-2" @if($extras) style="display: block" @else style="display: none" @endif>
                                        <label for="site_categories_array.{{$key}}.headerText" class="form-label">{{ ucfirst($category_site['category_url']) }} header text category</label>
                                        <input wire:model.lazy="site_categories_array.{{$key}}.headerText" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    </div>
                                    <div class="form-group mb-0 mt-2" @if($extras) style="display: block" @else style="display: none" @endif>
                                        <label for="site_categories_array.{{$key}}.footerText" class="form-label">{{ ucfirst($category_site['category_url']) }} footer text category</label>
                                        <input wire:model.lazy="site_categories_array.{{$key}}.footerText" type="text" maxlength="160" class="form-control" :errors="$errors" autocomplete="off" />
                                    </div>
                                @endforeach
                                <hr>
                            @endif --}}

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
                                <label for="slider_category">{{__('Slider category')}}</label>
                                <input type="file" class="form-control-file @error('slider_category') is_error @enderror" id="slider_category" wire:model.lazy="slider_category" />
                                @if($slider_category)
                                    <img src="{{ $slider_category->temporaryUrl() }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @if($preview)
                                    <img src="{{ $preview }}" class="img-thumbnail w-100 mt-3" />
                                @endif
                                @error('slider_category')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" @if($extras) style="display: block" @else style="display: none" @endif>
                                <label for="footer" class="form-label">{{__('Footer column 1')}} </label>
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
                                <label for="footer2" class="form-label">{{__('Footer column 2')}} </label>
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
                                <label for="footer3" class="form-label">{{__('Footer column 3')}} </label>
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
                                <label for="footer4" class="form-label">{{__('Footer column 4')}} </label>
                                <div class="mb-3" id="quill_edit10" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor10"
                                         x-init="
                                             quill_data10 = '{{ $footer4 }}';
                                             quill_edit10 = new Quill($refs.quillEditor10, {theme: 'snow'});
                                             quill_edit10.on('text-change', function () {
                                               $dispatch('input', quill_edit10.root.innerHTML);
                                               @this.set('footer4', quill_edit10.root.innerHTML)
                                             });
                                        "
                                         wire:model.lazy="footer4"
                                    >
                                        {!! $footer4 !!}
                                    </div>
                                </div>
                                @error('footer4')<span class="error">{{ $message }}</span>@enderror
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
                                <div class="w-100 d-flex justify-content-between">
                                    <label for="category" class="form-label">{{__('Categories for the homepage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model.lazy="categories_id" type="hidden" />
                                    <div>
                                        <input class="mr-1" type="checkbox" id="selectEditAllCategories" >{{ __('Select All')}}
                                    </div>
                                </div>
                                <select wire:model="category" class="form-control" multiple="multiple" id="select2-edit-categories" :errors="$errors" autocomplete="off">
                                    @if(!empty($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group" wire:ignore>
                                <div class="w-100 d-flex justify-content-between">
                                    <label for="subcategory" class="form-label">{{__('Daughters pages')}}</label>
                                     <input wire:model.lazy="subcategories_id" type="hidden" />
                                    <div>
                                        <input class="mr-1" type="checkbox" id="selectEditAllSubCategories" >{{ __('Select All')}}
                                    </div>
                                </div>
                                <select wire:model="subcategory" class="form-control" multiple="multiple" id="select2-edit-subcategories" :errors="$errors" autocomplete="off">
                                    @if(!empty($subcategories))
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
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

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="permanent" wire:model.lazy="permanent">
                                <label class="form-check-label" for="permanent">
                                    {{__('Permanent link')}}
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="no_index_follow" wire:model.lazy="no_index_follow">
                                <label class="form-check-label" for="no_index_follow">
                                    {{__('No index follow')}}
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

        <div wire:ignore.self class="modal fade" id="editTexts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Texts on the site')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form pt-4">
                            <div class="form-group">
                                <label for="headerText" class="form-label">{{__('Header text')}} </label>
                                <div class="mb-3" id="quill_edit4" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor4"
                                         x-init="
                                     quill_data4 = '{{ $headerText }}';
                                     quill_edit4 = new Quill($refs.quillEditor4, {theme: 'snow'});
                                     quill_edit4.on('text-change', function () {
                                       $dispatch('input', quill_edit4.root.innerHTML);
                                       @this.set('headerText', quill_edit4.root.innerHTML)
                                     });
                                "
                                         wire:model.lazy="headerText"
                                    >
                                        {!! $headerText !!}
                                    </div>
                                </div>
                                @error('headerText')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footerText" class="form-label">{{__('Footer text')}} </label>
                                <div class="mb-3" id="quill_edit5" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor5"
                                         x-init="
                                     quill_data5 = '{{ $footerText }}';
                                     quill_edit5 = new Quill($refs.quillEditor5, {theme: 'snow'});
                                     quill_edit5.on('text-change', function () {
                                       $dispatch('input', quill_edit5.root.innerHTML);
                                       @this.set('footerText', quill_edit5.root.innerHTML)
                                     });
                                "
                                         wire:model.lazy="footerText"
                                    >
                                        {!! $footerText !!}
                                    </div>
                                </div>
                                @error('footerText')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footer" class="form-label">{{__('Blog header')}} </label>
                                <div class="mb-3" id="quill_edit6" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor6"
                                         x-init="
                                         quill_data6 = '{{ $blog_header }}';
                                         quill_edit6 = new Quill($refs.quillEditor6, {theme: 'snow'});
                                            quill_edit6.on('text-change', function () {
                                                $dispatch('input', quill_edit6.root.innerHTML);
                                                @this.set('blog_header', quill_edit6.root.innerHTML)
                                            });"
                                         wire:model.lazy="blog_header">
                                        {!! $blog_header !!}
                                    </div>
                                </div>
                                @error('blog_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footer" class="form-label">{{__('Blog footer')}} </label>
                                <div class="mb-3" id="quill_edit7" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor7"
                                         x-init="
                                         quill_data7 = '{{ $blog_footer }}';
                                         quill_edit7 = new Quill($refs.quillEditor7, {theme: 'snow'});
                                         quill_edit7.on('text-change', function () {
                                                $dispatch('input', quill_edit7.root.innerHTML);
                                                @this.set('blog_footer', quill_edit7.root.innerHTML)
                                            });"
                                         wire:model.lazy="blog_footer">
                                        {!! $blog_footer !!}
                                    </div>
                                </div>
                                @error('blog_footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footer" class="form-label">{{__('Categories header')}} </label>
                                <div class="mb-3" id="quill_edit8" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor8"
                                         x-init="
                                         quill_data8 = '{{ $daughter_header }}';
                                         quill_edit8 = new Quill($refs.quillEditor8, {theme: 'snow'});
                                         quill_edit8.on('text-change', function () {
                                                $dispatch('input', quill_edit8.root.innerHTML);
                                                @this.set('daughter_header', quill_edit8.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_header">
                                        {!! $daughter_header !!}
                                    </div>
                                </div>
                                @error('daughter_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footer" class="form-label">{{__('Categories footer')}} </label>
                                <div class="mb-3" id="quill_edit9" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor9"
                                         x-init="
                                         quill_data9 = '{{ $daughter_footer }}';
                                         quill_edit9 = new Quill($refs.quillEditor9, {theme: 'snow'});
                                         quill_edit9.on('text-change', function () {
                                                $dispatch('input', quill_edit9.root.innerHTML);
                                                @this.set('daughter_footer', quill_edit9.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_footer">
                                        {!! $daughter_footer !!}
                                    </div>
                                </div>
                                @error('daughter_footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footer" class="form-label">{{__('Daughter header')}} </label>
                                <div class="mb-3" id="quill_edit11" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor11"
                                         x-init="
                                         quill_data11 = '{{ $daughter_home_header }}';
                                         quill_edit11 = new Quill($refs.quillEditor11, {theme: 'snow'});
                                         quill_edit11.on('text-change', function () {
                                                $dispatch('input', quill_edit11.root.innerHTML);
                                                @this.set('daughter_home_header', quill_edit11.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_home_header">
                                        {!! $daughter_home_header !!}
                                    </div>
                                </div>
                                @error('daughter_home_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footer" class="form-label">{{__('Daughter footer')}} </label>
                                <div class="mb-3" id="quill_edit12" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor12"
                                         x-init="
                                         quill_data12 = '{{ $daughter_home_footer }}';
                                         quill_edit12 = new Quill($refs.quillEditor12, {theme: 'snow'});
                                         quill_edit12.on('text-change', function () {
                                                $dispatch('input', quill_edit12.root.innerHTML);
                                                @this.set('daughter_home_footer', quill_edit12.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_home_footer">
                                        {!! $daughter_home_footer !!}
                                    </div>
                                </div>
                                @error('daughter_home_footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footer" class="form-label">{{__('Daughter blog header')}} </label>
                                <div class="mb-3" id="quill_edit13" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor13"
                                         x-init="
                                         quill_data13 = '{{ $daughter_blog_header }}';
                                         quill_edit13 = new Quill($refs.quillEditor13, {theme: 'snow'});
                                         quill_edit13.on('text-change', function () {
                                                $dispatch('input', quill_edit13.root.innerHTML);
                                                @this.set('daughter_blog_header', quill_edit13.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_blog_header">
                                        {!! $daughter_blog_header !!}
                                    </div>
                                </div>
                                @error('daughter_blog_header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footer" class="form-label">{{__('Daughter blog footer')}} </label>
                                <div class="mb-3" id="quill_edit14" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor14"
                                         x-init="
                                         quill_data14 = '{{ $daughter_blog_footer }}';
                                         quill_edit14 = new Quill($refs.quillEditor14, {theme: 'snow'});
                                         quill_edit14.on('text-change', function () {
                                                $dispatch('input', quill_edit14.root.innerHTML);
                                                @this.set('daughter_blog_footer', quill_edit14.root.innerHTML)
                                            });"
                                         wire:model.lazy="daughter_blog_footer">
                                        {!! $daughter_blog_footer !!}
                                    </div>
                                </div>
                                @error('daughter_blog_footer')<span class="error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editTexts">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editTexts">
                            <button type="button" wire:click="editTexts" class="btn btn-primary">{{__('Save')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
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

        <div wire:ignore.self class="modal fade" id="extraSettings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Extra settings')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="form-group">
                            <label for="google_analytics_code" class="form-label">{{__('Google Analytic Code')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                            {{-- <input wire:model.lazy="google_analytics_code" id="google_analytics_code" type="text" class="form-control" :errors="$errors" autocomplete="off" /> --}}
                            <textarea cols="50" rows="10" class="form-control" wire:model="google_analytics_code"></textarea>
                            @error('google_analytics_code') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" wire:click="addExtraSettings">{{__('Save data')}}</button>
                        <button type="button" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Cancel')}}</button>
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

        $("#selectAllSubCategories").click(function(){
            if($("#selectAllSubCategories").is(':checked') ){
                $("#select2-subcategories > option").prop("selected","selected");
                $("#select2-subcategories").trigger("change");
            }else{
                $('select#select2-subcategories').val(null).trigger('change');
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

        $("#selectEditAllSubCategories").click(function(){
            if($("#selectEditAllSubCategories").is(':checked') ){
                $("#select2-edit-subcategories > option").prop("selected","selected");
                $("#select2-edit-subcategories").trigger("change");
            }else{
                $('select#select2-edit-subcategories').val(null).trigger('change');
            }
        });

        $("#selectEditAllUsers").click(function(){
            if($("#selectEditAllUsers").is(':checked') ){
                $("#select2-edit-users > option").prop("selected","selected");
                $("#select2-edit-users").trigger("change");
            }else{
                $('select#select2-edit-users').val(null).trigger('change');
            }
        });

        $("#selectAllUsers").click(function(){
            if($("#selectAllUsers").is(':checked') ){
                $("#select2-users > option").prop("selected","selected");
                $("#select2-users").trigger("change");
            }else{
                $('select#select2-users').val(null).trigger('change');
            }
        });
        
        document.addEventListener('livewire:load', function () {    

            $('#select2-users').select2();
            $('#select2-edit-users').select2();
            $('#select2-categories').select2();
            $('#select2-subcategories').select2();
            $('#select2-edit-categories').select2();
            $('#select2-edit-subcategories').select2();

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

        window.addEventListener('resetEditUsers', event => {
            $('#select2-edit-users').empty();
            $('#select2-edit-users').select2({data: event.detail.options, width: '100%'});
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
            $('select#select2-users').val(null).trigger('change');
            quill.container.firstChild.innerHTML  = '';
            quill2.container.firstChild.innerHTML = '';
            quill3.container.firstChild.innerHTML = '';
            // quill4.container.firstChild.innerHTML  = '';
            // quill5.container.firstChild.innerHTML  = '';
            // quill6.container.firstChild.innerHTML  = '';
            // quill7.container.firstChild.innerHTML  = '';
            // quill8.container.firstChild.innerHTML  = '';
            // quill9.container.firstChild.innerHTML  = '';
            quill10.container.firstChild.innerHTML  = '';
            // quill11.container.firstChild.innerHTML  = '';
            // quill12.container.firstChild.innerHTML  = '';
            // quill13.container.firstChild.innerHTML  = '';
            // quill14.container.firstChild.innerHTML  = '';
            //$('#addSite').modal('show');
            $('#addSite').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideAddSite', event => {
            $('#addSite').modal('hide');
        });

        window.addEventListener('showEditSite', event => {
            quill_edit.setContents(quill_edit.clipboard.convert(event.detail.editor), 'silent');
            quill_edit2.setContents(quill_edit2.clipboard.convert(event.detail.footer2), 'silent');
            quill_edit3.setContents(quill_edit3.clipboard.convert(event.detail.footer3), 'silent');
            quill_edit4.setContents(quill_edit4.clipboard.convert(event.detail.headerText), 'silent');
            quill_edit5.setContents(quill_edit5.clipboard.convert(event.detail.footerText), 'silent');
            quill_edit6.setContents(quill_edit6.clipboard.convert(event.detail.blog_header), 'silent');
            quill_edit7.setContents(quill_edit7.clipboard.convert(event.detail.blog_footer), 'silent');
            quill_edit8.setContents(quill_edit8.clipboard.convert(event.detail.daughter_header), 'silent');
            quill_edit9.setContents(quill_edit9.clipboard.convert(event.detail.daughter_footer), 'silent');
            quill_edit10.setContents(quill_edit10.clipboard.convert(event.detail.footer4), 'silent');
            quill_edit11.setContents(quill_edit11.clipboard.convert(event.detail.daughter_home_header), 'silent');
            quill_edit12.setContents(quill_edit12.clipboard.convert(event.detail.daughter_home_footer), 'silent');
            quill_edit13.setContents(quill_edit13.clipboard.convert(event.detail.daughter_blog_header), 'silent');
            quill_edit14.setContents(quill_edit14.clipboard.convert(event.detail.daughter_blog_footer), 'silent');
            //$('#editSite').modal('show');
            $('#editSite').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('showEditCategories', event => {
            //$('#editCategories').modal('show');
            $('#editCategories').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });
        
        window.addEventListener('showEditTexts', event => {
            quill_edit.setContents(quill_edit.clipboard.convert(event.detail.editor), 'silent');
            quill_edit2.setContents(quill_edit2.clipboard.convert(event.detail.footer2), 'silent');
            quill_edit3.setContents(quill_edit3.clipboard.convert(event.detail.footer3), 'silent');
            quill_edit4.setContents(quill_edit4.clipboard.convert(event.detail.headerText), 'silent');
            quill_edit5.setContents(quill_edit5.clipboard.convert(event.detail.footerText), 'silent');
            quill_edit6.setContents(quill_edit6.clipboard.convert(event.detail.blog_header), 'silent');
            quill_edit7.setContents(quill_edit7.clipboard.convert(event.detail.blog_footer), 'silent');
            quill_edit8.setContents(quill_edit8.clipboard.convert(event.detail.daughter_header), 'silent');
            quill_edit9.setContents(quill_edit9.clipboard.convert(event.detail.daughter_footer), 'silent');
            quill_edit10.setContents(quill_edit10.clipboard.convert(event.detail.footer4), 'silent');
            quill_edit11.setContents(quill_edit11.clipboard.convert(event.detail.daughter_home_header), 'silent');
            quill_edit12.setContents(quill_edit12.clipboard.convert(event.detail.daughter_home_footer), 'silent');
            quill_edit13.setContents(quill_edit13.clipboard.convert(event.detail.daughter_blog_header), 'silent');
            quill_edit14.setContents(quill_edit14.clipboard.convert(event.detail.daughter_blog_footer), 'silent');
            //$('#editCategories').modal('show');
            $('#editTexts').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('showExtraSettings', event => {
            $('#extraSettings').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideExtraSettings', event => {
            $('#extraSettings').modal('hide');
        });

        window.addEventListener('hideEditCategories', event => {
            $('#editCategories').modal('hide');
        });

        window.addEventListener('hideEditTexts', event => {
            $('#editTexts').modal('hide');
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
