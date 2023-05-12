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
                @if(permission('packages', 'create'))
                    @if($tab == 'packages')
                        <a data-toggle="modal" wire:click="modalAddPackage"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add package')}}</span></a>
                    @endif
                    @if($tab == 'categories')
                        <a data-toggle="modal" wire:click="modalAddCategory"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add category')}}</span></a>
                    @endif
                @endif
            </div>
        </div>

        <div class="cont ">
            <div class="card">
                <div class="card-body">
                    <div class="tab">
                        <a @if($tab == 'packages') class="active" @endif wire:click="table('packages')">{{__('Packages') }}</a>
                        <a @if($tab == 'categories') class="active" @endif wire:click="table('categories')">{{__('Categories') }}</a>
                    </div>
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

                        @if($tab == 'packages')
                            <table class="table table-hover table-packages">
                                <thead>
                                    <tr>
                                        <th>{{__('Name')}} <a wire:click="sort('packages', 'name')"><i class="fas fa-sort"></i></a></th>
                                        <th>{{__('Language')}} <a wire:click="sort('packages', 'language')"><i class="fas fa-sort"></i></a></th>
                                        <th>{{__('Websites')}} <a wire:click="sort('packages', 'pages')"><i class="fas fa-sort"></i></a></th>
                                        <th>{{__('Price')}} <a wire:click="sort('packages', 'price')"><i class="fas fa-sort"></i></a></th>
                                        <th>{{__('Actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(!empty($packages))
                                    @foreach($packages as $row)
                                        <tr>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->languages ? $row->languages->description : '' }}</td>
                                            <td>{{ $row->pages ?? '0' }}</td>
                                            <td>{{ currency() }} {{ $row->price }}</td>
                                            <td>
                                                @if(permission('packages', 'update'))
                                                    <a class="blues" wire:click="modalEditPackage({{$row->id}})" alt="{{__('Edit package')}}" title="{{__('Edit package')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('packages', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$row->id}})" alt="{{__('Delete package')}}" title="{{__('Delete package')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have packages added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($packages))
                                {{ $packages->links() }}
                            @endif
                        @endif

                        @if($tab == 'categories')
                            <table class="table table-hover table-packages">
                                <thead>
                                <tr>
                                    <th>{{__('Name')}} <a wire:click="sort('packages_categories', 'name')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($categories_list))
                                    @foreach($categories_list as $row)
                                        <tr>
                                            <td>{{ $row->name }}</td>
                                            <td>
                                                @if(permission('packages', 'update'))
                                                    <a class="blues" wire:click="modalEditCategory({{$row->id}})" alt="{{__('Edit category')}}" title="{{__('Edit category')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('packages', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$row->id}})" alt="{{__('Delete category')}}" title="{{__('Delete category')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have packages categories added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($categories_list))
                                {{ $categories_list->links() }}
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="addPackage, editPackage, addCategory, editCategory, sort, delete">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="addPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create package')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successPackage'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successPackage') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="name" type="text" class="form-control" maxlength="75" :errors="$errors" autocomplete="off" />
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <textarea wire:model="description" class="form-control" maxlength="250" :errors="$errors"></textarea>
                                @error('description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="price" class="form-label">{{__('Price')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="price" type="number" min="0" class="form-control" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                @error('price') <span class="error">{{ $message }}</span> @enderror
                                @if(!empty($suggested_price) and intval($suggested_price) > 0)
                                    <small>{{__('Normal price')}}: {{ currency() }} {{ get_price($suggested_price) }}</small>
                                @endif
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
                            <div class="form-group">
                                <label for="category" class="form-label">{{__('Category')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="category" id="category" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($categories))
                                        @foreach($categories as $option)
                                            <option value="{{ $option->id }}">{{ $option->text }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('category') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group" wire:ignore>
                                <label for="select2-sites" class="form-label">{{__('Sites')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select id="select2-sites" class="form-control" multiple="multiple" :errors="$errors" autocomplete="off">
                                </select>
                                @error('site') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="packages_categories" class="form-label">{{__('Package category')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select id="packages_categories" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($packages_categories))
                                        @foreach($packages_categories as $option)
                                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('packages_category') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addPackage">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addPackage">
                            <button type="button" wire:click="addPackage" class="btn btn-primary">{{__('Save package')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit package')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successPackage'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successPackage') }}
                                </div>
                            @endif
                            <input type="hidden" wire:model="package" />
                            <div class="form-group">
                                <label for="name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="name" type="text" class="form-control" maxlength="75" :errors="$errors" autocomplete="off" />
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <textarea wire:model="description" class="form-control" maxlength="250" :errors="$errors"></textarea>
                                @error('description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="price" class="form-label">{{__('Price')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="price" type="number" min="0" class="form-control" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                @error('price') <span class="error">{{ $message }}</span> @enderror
                                @if(!empty($suggested_price) and intval($suggested_price) > 0)
                                    <small>{{__('Normal price')}}: {{ currency() }} {{ get_price($suggested_price) }}</small>
                                @endif
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
                            <div class="form-group">
                                <label for="category_edit" class="form-label">{{__('Category')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model.lazy="category" id="category_edit" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($categories))
                                        @foreach($categories as $option)
                                            <option value="{{ $option->id }}">{{ $option->text }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('category') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group" wire:ignore>
                                <label for="select2-edit-sites" class="form-label">{{__('Sites')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select id="select2-edit-sites" class="form-control" multiple="multiple" :errors="$errors" autocomplete="off">
                                </select>
                                @error('site') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit_packages_categories" class="form-label">{{__('Package category')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select id="edit_packages_categories" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($packages_categories))
                                        @foreach($packages_categories as $option)
                                            <option value="{{ $option->id }}" @if($option->id == $packages_category) selected="selected" @endif>{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('packages_category') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editPackage">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editPackage">
                            <button type="button" wire:click="editPackage" class="btn btn-primary">{{__('Edit package')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create category')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successCategory'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successCategory') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="category_name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="category_name" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('category_name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addCategory">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addCategory">
                            <button type="button" wire:click="addCategory" class="btn btn-primary">{{__('Save category')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit category')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successCategory'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successCategory') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="category_name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="category_name" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('category_name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editCategory">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editCategory">
                            <button type="button" wire:click="editCategory" class="btn btn-primary">{{__('Edit category')}}</button>
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
                        <p>{{__('Are you sure want to delete this :what?', ['what' => $what])}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="delete" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#select2-sites').select2();
            $('#select2-edit-sites').select2();
        });

        document.addEventListener('livewire:load', function () {
            $(document).on('change', '#select2-sites', function() {
                let data = ($('#select2-sites').select2("val"));
                @this.set('site', data);
            });

            $(document).on('change', '#select2-edit-sites', function() {
                let data = ($('#select2-edit-sites').select2("val"));
                @this.set('site', data);
            });

            $(document).on('change', '#packages_categories', function() {
                @this.set('packages_category', $(this).val());
            });

            $(document).on('change', '#edit_packages_categories', function() {
                @this.set('packages_category', $(this).val());
            });
        });

        window.addEventListener('showAddPackage', event => {
            $('#addPackage').modal('show');
        });

        window.addEventListener('hideAddPackage', event => {
            $('#addPackage').modal('hide');
        });

        window.addEventListener('showEditPackage', event => {
            $('#editPackage').modal('show');
        });

        window.addEventListener('hideEditPackage', event => {
            $('#editPackage').modal('hide');
        });

        window.addEventListener('showAddCategory', event => {
            $('#addCategory').modal('show');
        });

        window.addEventListener('resetCategory', event => {
            $('#packages_categories').prop('selectedIndex', 0);
            $('select#select2-sites').val(null).trigger('change');
        });

        window.addEventListener('hideAddCategory', event => {
            $('#addCategory').modal('hide');
        });

        window.addEventListener('showEditCategory', event => {
            $('#editCategory').modal('show');
        });

        window.addEventListener('hideEditCategory', event => {
            $('#editCategory').modal('hide');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('loadSites', event => {
            $('#select2-sites').select2('destroy');
            $('#select2-sites').select2({data: event.detail.options, width: '100%'});
        });

        window.addEventListener('editSites', event => {
            $('#select2-edit-sites').empty();
            $('#select2-edit-sites').select2({data: event.detail.options, width: '100%'});
        });
    </script>
@endpush
