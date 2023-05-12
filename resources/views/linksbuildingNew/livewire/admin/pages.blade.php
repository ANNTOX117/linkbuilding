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
                @if(permission('pages', 'create'))
                    <a data-toggle="modal" wire:click="modalAddPage"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add page')}}</span></a>
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
                                <th>{{__('Title')}} <a wire:click="sort('name')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('URL')}} <a wire:click="sort('title')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Language')}} <a wire:click="sort('title')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($pages))
                                @foreach($pages as $page)
                                    <tr>
                                        <td>{{ $page->title }}</td>
                                        <td>{{ $page->url }}</td>
                                        <td>{{ !empty($page->languages) ? $page->languages->description : '-' }}</td>
                                        <td>
                                            @if(permission('pages', 'update'))
                                                <a class="blues" wire:click="modalEditPage({{$page->id}})" alt="{{__('Edit page')}}" title="{{__('Edit page')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                            @endif
                                            @if(permission('pages', 'delete'))
                                                <a class="reds" wire:click="confirm({{$page->id}})" alt="{{__('Delete page')}}" title="{{__('Delete page')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have pages added yet')}}</em></div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        @if(!empty($pages))
                            {{ $pages->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="addPage, editPage, sort, delete">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create page')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successPage'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successPage') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="url" class="form-label">{{__('URL')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="url" type="text" class="form-control" maxlength="255" placeholder="/" :errors="$errors" autocomplete="off" />
                                @error('url') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="all_cities" wire:model="all_cities">
                                <label class="form-check-label" for="all_cities">{{__("For all cities (if it applies)")}}</label>
                            </div>
                            <div class="form-group">
                                <label for="language" class="form-label">{{__('Language')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
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
                            <div class="form-group">
                                <label for="title" class="form-label">{{__('Title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="name" type="text" class="form-control" maxlength="120" :errors="$errors" autocomplete="off" placeholder="{{__("Title [city/province]")}}"/>
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="content_top" class="form-label">{{__('Content top')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor"
                                         x-init="quill = new Quill($refs.quillEditor, {theme: 'snow'});
                                            quill.on('text-change', function () {
                                                $dispatch('input', quill.root.innerHTML);
                                                @this.set('content_top', quill.root.innerHTML)
                                            });"
                                         wire:model.lazy="content_top">
                                        {!! $content_top !!}
                                    </div>
                                </div>
                                @error('content_top')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="content_buttom" class="form-label">{{__('content buttom')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor"
                                         x-init="quill2 = new Quill($refs.quillEditor, {theme: 'snow'});
                                            quill2.on('text-change', function () {
                                                $dispatch('input', quill2.root.innerHTML);
                                                @this.set('content_buttom', quill2.root.innerHTML)
                                            });"
                                         wire:model.lazy="content_buttom">
                                        {!! $content_buttom !!}
                                    </div>
                                </div>
                                @error('content_buttom')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_title" class="form-label">{{__('Meta title')}}</label>
                                <input wire:model="meta_title" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" placeholder="{{__("Meta title [city/province]")}}"/>
                                @error('meta_title') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_description" class="form-label">{{__('Meta description')}}</label>
                                <input wire:model="meta_description" type="text" class="form-control" :errors="$errors" autocomplete="off" placeholder="{{__("Meta description [city/province]")}}"/>
                                @error('meta_description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        
                            <div class="form-group">
                                <label for="meta_keyword" class="form-label">{{__('Meta keyword')}}</label>
                                <input wire:model="meta_keyword" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" placeholder="{{__("Meta keyword [city/province]")}}"/>
                                @error('meta_keyword') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="noindex_follow" wire:model="noindex_follow">
                                <label class="form-check-label" for="noindex_follow">{{__("Index no follow")}}</label>
                            </div>
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label for="select2-sites" class="form-label">{{__('Sites')}}</label>
                                    <select wire:model="site" class="form-control" multiple="multiple" id="select2-sites" :errors="$errors" autocomplete="off">
                                        <option value="0">{{__('All websites')}}</option>
                                        @if(!empty($sites))
                                            @foreach($sites as $item)
                                                <option value="{{ $item->id }}">{{ $item->text }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <span class="text-muted">{{__('Required fields')}}</span></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addPage">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addPage">
                            <button type="button" wire:click="addPage" class="btn btn-primary">{{__('Save page')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editPage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit page')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successPage'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successPage') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="url" class="form-label">{{__('URL')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="url" type="text" class="form-control" maxlength="255" placeholder="/" :errors="$errors" autocomplete="off" />
                                @error('url') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="all_cities" wire:model="all_cities">
                                <label class="form-check-label" for="all_cities">{{__("For all cities (if it applies)")}}</label>
                            </div>
                            <div class="form-group">
                                <label for="edit_language" class="form-label">{{__('Language')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="language" id="edit_language" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($languages))
                                        @foreach($languages as $language)
                                            <option value="{{ $language->id }}">{{ $language->description }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('language') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="title" class="form-label">{{__('Title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="name" type="text" class="form-control" maxlength="120" :errors="$errors" autocomplete="off" placeholder="{{__("Title [city/province]")}}"/>
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="content_top" class="form-label">{{__('Content top')}}</label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor"
                                         x-init="
                                             quill_data = '{{ $content_top }}';
                                             quill_edit_top = new Quill($refs.quillEditor, {theme: 'snow'});
                                             quill_edit_top.on('text-change', function () {
                                               $dispatch('input', quill_edit_top.root.innerHTML);
                                               @this.set('content_top', quill_edit_top.root.innerHTML)
                                             });
                                        "
                                         wire:model.lazy="content_top"
                                    >
                                        {!! $content_top !!}
                                    </div>
                                </div>
                                @error('content_top')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="content_buttom" class="form-label">{{__('Content buttom')}}</label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor"
                                         x-init="
                                             quill_data = '{{ $content_buttom }}';
                                             quill_edit_buttom = new Quill($refs.quillEditor, {theme: 'snow'});
                                             quill_edit_buttom.on('text-change', function () {
                                               $dispatch('input', quill_edit_buttom.root.innerHTML);
                                               @this.set('content_buttom', quill_edit_buttom.root.innerHTML)
                                             });
                                        "
                                         wire:model.lazy="content_buttom"
                                    >
                                        {!! $content_buttom !!}
                                    </div>
                                </div>
                                @error('content_buttom')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_title" class="form-label">{{__('Meta title')}}</label>
                                <input wire:model="meta_title" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" placeholder="{{__("Meta title [city/province]")}}" />
                                @error('meta_title') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_description" class="form-label">{{__('Meta description')}}</label>
                                <input wire:model="meta_description" type="text" class="form-control" :errors="$errors" autocomplete="off" placeholder="{{__("Meta description [city/province]")}}"/>
                                @error('meta_description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_keyword" class="form-label">{{__('Meta keyword')}}</label>
                                <input wire:model="meta_keyword" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" placeholder="{{__("Meta keyword [city/province]")}}"/>
                                @error('meta_keyword') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="noindex_follow" wire:model="noindex_follow">
                                <label class="form-check-label" for="noindex_follow">Index no follow</label>
                            </div>
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label for="select2-sites-edit" class="form-label">{{__('Sites')}}</label>
                                    <select wire:model="site" class="form-control" multiple="multiple" id="select2-sites-edit" :errors="$errors" autocomplete="off">
                                        <option value="0">{{__('All websites')}}</option>
                                        @if(!empty($sites))
                                            @foreach($sites as $item)
                                                <option value="{{ $item->id }}">{{ $item->text }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <span class="text-muted">{{__('Required fields')}}</span></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editPage">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editPage">
                            <button type="button" wire:click="editPage" class="btn btn-primary">{{__('Edit page')}}</button>
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
                        <p>{{__('Are you sure want to delete this page?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="delete" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Error')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p>{{ $custom_error }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger close-btn" data-dismiss="modal">{{__('OK')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            $("#select2-sites, #select2-sites-edit").select2({ width: '100%' });
            $('#select2-sites, #select2-sites-edit').on('change', function (e) {
                let data = ($(this).select2("val"));
                @this.set('site', data);
            });
        });

        window.addEventListener('resetOptions', event => {
            $('#select2-sites-edit').empty();
            $('#select2-sites-edit').select2({data: event.detail.options, width: '100%'});
        });

        window.addEventListener('showAddPage', event => {
            quill.container.firstChild.innerHTML = '';
            quill2.container.lastChild.innerHTML = '';
            //$('#addPage').modal('show');
            $('#addPage').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideAddPage', event => {
            $('#addPage').modal('hide');
        });

        window.addEventListener('showEditPage', event => {
            quill_edit_top.container.firstChild.innerHTML = event.detail.content_top;
            quill_edit_buttom.container.firstChild.innerHTML = event.detail.content_buttom;
            //$('#editPage').modal('show');
            $('#editPage').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideEditPage', event => {
            $('#editPage').modal('hide');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('showError', event => {
            $('#errorModal').modal('show');
        });
    </script>
@endpush
