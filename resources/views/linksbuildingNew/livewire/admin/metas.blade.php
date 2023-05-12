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
                    <a data-toggle="modal" wire:click="modalAddPage"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add options')}}</span></a>
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
                                <th>{{__('Category')}} <a wire:click="sort('name')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('URL')}} <a wire:click="sort('name')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Site')}} <a wire:click="sort('title')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($pages))
                                @foreach($pages as $page)
                                    <tr>
                                        <td>{{ __(isset($page->category_name)?$page->category_name:"Without category") }}</td>
                                        <td>{{ $page->url }}</td>
                                        <td>{{ $page->site_name }}</td>
                                        <td>
                                            <a class="blues" wire:click="modalEditPage({{$page->id}})" alt="{{__('Edit options')}}" title="{{__('Edit options')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                            <a class="reds" wire:click="confirm({{$page->id}})" alt="{{__('Delete options')}}" title="{{__('Delete options')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
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
                        <h4 class="modal-title">{{__('Save options')}}</h4>
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
                            <div class="form-group">
                                <label for="header" class="form-label">{{__('Header')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor1"
                                         x-init="quill1 = new Quill($refs.quillEditor1, {theme: 'snow'});
                                            quill1.on('text-change', function () {
                                                $dispatch('input', quill1.root.innerHTML);
                                                @this.set('header', quill1.root.innerHTML)
                                            });"
                                         wire:model.lazy="header">
                                        {!! $header !!}
                                    </div>
                                </div>
                                @error('header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footer" class="form-label">{{__('Footer')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" wire:ignore>
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
                            <div class="form-group">
                                <label for="meta_title" class="form-label">{{__('Meta title')}}</label>
                                <input wire:model="meta_title" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('meta_title') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_description" class="form-label">{{__('Meta description')}}</label>
                                <input wire:model="meta_description" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('meta_description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_keyword" class="form-label">{{__('Meta keyword')}}</label>
                                <input wire:model="meta_keyword" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('meta_keyword') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group" wire:ignore>
                                <label for="site" class="form-label">{{__('Site')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="site_id" id="select2-sites" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose a site')}}</option>
                                    @if(!empty($sites))
                                        @foreach($sites as $item)
                                            <option value="{{ $item->id }}">{{ $item->text }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('site') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group" wire:ignore>
                                <label class="form-label">{{__('Category')}}</label>
                                <select wire:model="category_id" id="select2-category" class="form-control" :errors="$errors">
                                    <option id="">{{"Select category"}}</option>
                                        @if(!empty($categories))
                                            @foreach($categories as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                </select>
                                @error('category_id') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            {{-- <div>
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
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label for="select2-sites" class="form-label">{{__('Category')}}</label>
                                    <select wire:model="site" class="form-control" multiple="multiple" id="select2-sites" :errors="$errors" autocomplete="off">
                                        <option value="0">{{__('Select category')}}</option>
                                        @if(!empty($categories))
                                            @foreach($categories as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div> --}}
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
                        <h4 class="modal-title">{{__('Edit options')}}</h4>
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
                            {{-- <div class="form-group">
                                <label for="url" class="form-label">{{__('URL')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="url" type="text" class="form-control" maxlength="255" placeholder="/" :errors="$errors" autocomplete="off" />
                                @error('url') <span class="error">{{ $message }}</span> @enderror
                            </div> --}}
                            <div class="form-group">
                                <label for="header" class="form-label">{{__('Header')}}</label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor1"
                                         x-init="
                                             quill_data1 = '{{ $header }}';
                                             quill_edit1 = new Quill($refs.quillEditor1, {theme: 'snow'});
                                             quill_edit1.on('text-change', function () {
                                               $dispatch('input', quill_edit1.root.innerHTML);
                                               @this.set('header', quill_edit1.root.innerHTML)
                                             });
                                        "
                                         wire:model.lazy="header"
                                    >
                                        {!! $header !!}
                                    </div>
                                </div>
                                @error('header')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="footer" class="form-label">{{__('Footer')}}</label>
                                <div class="mb-3" wire:ignore>
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
                            <div class="form-group">
                                <label for="meta_title" class="form-label">{{__('Meta title')}}</label>
                                <input wire:model="meta_title" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('meta_title') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_description" class="form-label">{{__('Meta description')}}</label>
                                <input wire:model="meta_description" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('meta_description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_keyword" class="form-label">{{__('Meta keyword')}}</label>
                                <input wire:model="meta_keyword" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('meta_keyword') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label for="select2-sites-edit" class="form-label">{{__('Sites')}}</label>
                                    <select wire:model="site_id" class="form-control" id="select2-sites-edit" :errors="$errors" autocomplete="off">
                                        <option value="0">{{__('All websites')}}</option>
                                        @if(!empty($sites))
                                            @foreach($sites as $item)
                                                <option value="{{ $item->id }}">{{ $item->text }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label for="select2-category-edit" class="form-label">{{__('Category')}}</label>
                                    <select wire:model="category_id" class="form-control" id="select2-category-edit" :errors="$errors" autocomplete="off">
                                        @if(!empty($categories))
                                            <option value="0">{{__('Select category')}}</option>
                                            @foreach($categories as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                        <p>{{__('Are you sure want to delete this options?')}}</p>
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
                @this.set('site_id', data);
            });
            $("#select2-category, #select2-category-edit").select2({ width: '100%' });
            $('#select2-category, #select2-category-edit').on('change', function (e) {
                let data = ($(this).select2("val"));
                @this.set('category_id', data);
            });
        });

        window.addEventListener('resetOptions', event => {
            $('#select2-sites-edit').empty();
            $('#select2-sites-edit').select2({data: event.detail.options, width: '100%'});
            $('#select2-sites-edit').val(event.detail.selected_option).trigger('change');
        });

        window.addEventListener('resetCategories', event => {
            console.log(event.detail.options);
            event.detail.options.unshift({id:0,"text":"Select Category"})
            $('#select2-category, #select2-category-edit').empty();
            $('#select2-category, #select2-category-edit').select2({data: event.detail.options, width: '100%'});
            $('#select2-category-edit').val(event.detail.selected_option).trigger('change');
        });

        window.addEventListener('showAddPage', event => {
            quill.container.firstChild.innerHTML = '';
            quill1.container.firstChild.innerHTML = '';
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
            quill_edit.container.firstChild.innerHTML = event.detail.editor;
            quill_edit1.container.firstChild.innerHTML = event.detail.editor_1;
            // $('#editPage').modal('show');
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
        $('select').select2("val", null);
    </script>
@endpush
