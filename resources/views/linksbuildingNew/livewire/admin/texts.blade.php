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
                @if(permission('texts', 'create'))
                    <a data-toggle="modal" wire:click="modalAddText"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add text')}}</span></a>
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
                                <th>{{__('Title')}} <a wire:click="sort('title')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Language')}} <a wire:click="sort('language')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($texts))
                                @foreach($texts as $text)
                                    <tr>
                                        <td>{{ $text->name }}</td>
                                        <td>{{ $text->title }}</td>
                                        <td>{{ $text->languages ? $text->languages->description : '' }}</td>
                                        <td>
                                            @if(permission('texts', 'update'))
                                                <a class="blues" wire:click="modalEditText({{$text->id}})" alt="{{__('Edit text')}}" title="{{__('Edit text')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                            @endif
                                            @if(permission('texts', 'delete'))
                                                <a class="reds" wire:click="confirm({{$text->id}})" alt="{{__('Delete text')}}" title="{{__('Delete text')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have texts added yet')}}</em></div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        @if(!empty($texts))
                            {{ $texts->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="addText, editText, sort, delete">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="addText" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create text')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successText'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successText') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="text_name" class="form-label">{{__('Text name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="text_name" type="text" class="form-control" maxlength="140" :errors="$errors" autocomplete="off" />
                                @error('text_name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="text_title" class="form-label">{{__('Title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="text_title" type="text" class="form-control" maxlength="140" :errors="$errors" autocomplete="off" />
                                @error('text_title') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="text_description" class="form-label">{{__('Content')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor"
                                         x-init="quill = new Quill($refs.quillEditor, {theme: 'snow'});
                                            quill.on('text-change', function () {
                                                $dispatch('input', quill.root.innerHTML);
                                                @this.set('text_description', quill.root.innerHTML)
                                            });"
                                         wire:model.lazy="text_description">
                                        {!! $text_description !!}
                                    </div>
                                </div>
                                @error('text_description')<span class="error">{{ $message }}</span>@enderror
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
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addText">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addText">
                            <button type="button" wire:click="addText" class="btn btn-primary">{{__('Save text')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editText" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit text')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successText'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successText') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="text_name" class="form-label">{{__('Text name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="text_name" type="text" class="form-control" maxlength="140" :errors="$errors" autocomplete="off" />
                                @error('text_name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="text_title" class="form-label">{{__('Title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="text_title" type="text" class="form-control" maxlength="140" :errors="$errors" autocomplete="off" />
                                @error('text_title') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="text_description" class="form-label">{{__('Content')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor"
                                         x-init="
                                     quill_data = '{{ $text_description }}';
                                     quill_edit = new Quill($refs.quillEditor, {theme: 'snow'});
                                     quill_edit.on('text-change', function () {
                                       $dispatch('input', quill_edit.root.innerHTML);
                                       @this.set('text_description', quill_edit.root.innerHTML)
                                     });
                                "
                                         wire:model.lazy="text_description"
                                    >
                                        {!! $text_description !!}
                                    </div>
                                </div>
                                @error('text_description')<span class="error">{{ $message }}</span>@enderror
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
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editText">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editText">
                            <button type="button" wire:click="editText" class="btn btn-primary">{{__('Edit text')}}</button>
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
                        <p>{{__('Are you sure want to delete this text?')}}</p>
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
        window.addEventListener('showAddText', event => {
            quill.container.firstChild.innerHTML = '';
            //$('#addText').modal('show');
            $('#addText').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideAddText', event => {
            $('#addText').modal('hide');
        });

        window.addEventListener('showEditText', event => {
            quill_edit.container.firstChild.innerHTML = event.detail.editor;
            //$('#editText').modal('show');
            $('#editText').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideEditText', event => {
            $('#editText').modal('hide');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('showError', event => {
            $('#errorModal').modal('show');
        });
    </script>
@endpush
