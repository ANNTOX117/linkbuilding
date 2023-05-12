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
                @if(permission('mailing', 'create'))
                    <a data-toggle="modal" wire:click="modalAddEmail"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add email')}}</span></a>
                @endif
            </div>
        </div>

        <div class="cont ">
            <div class="card">
                <div class="card-body">
                    <div class="tab">
                        <a @if($tab == 'emails') class="active" @endif wire:click="table('emails')">{{__('Emails') }}</a>
                        <a @if($tab == 'batches') class="active" @endif wire:click="table('batches')">{{__('Batches')}}</a>
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

                        @if($tab == 'emails')
                            <table class="table table-hover table-links">
                                <thead>
                                <tr>
                                    <th>{{__('Name')}} <a wire:click="sort('mailing_text', 'name')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Type')}} <a wire:click="sort('mailing_text', 'type')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Language')}} <a wire:click="sort('mailing_text', 'language')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($emails))
                                    @foreach($emails as $email)
                                        <tr>
                                            <td>{{ $email->name }}</td>
                                            <td>{{ $email->type }}</td>
                                            <td>@if(!empty($email->languages)) {{ $email->languages->description }} @else - @endif</td>
                                            <td>
                                                @if(permission('mailing', 'update'))
                                                    <a class="blues" wire:click="modalSendEmail({{$email->id}})" alt="{{__('Send email')}}" title="{{__('Send email')}}"><span class="block"><i class="far fa-envelope-open"></i></span></a>
                                                    <a class="blues" wire:click="modalEditEmail({{$email->id}})" alt="{{__('Edit email')}}" title="{{__('Edit email')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('mailing', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$email->id}})" alt="{{__('Delete email')}}" title="{{__('Delete email')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have emails added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($emails))
                                {{ $emails->links() }}
                            @endif
                        @endif

                        @if($tab == 'batches')
                            <table class="table table-hover table-requests">
                                <thead>
                                <tr>
                                    <th>{{__('Subject')}} <a wire:click="sort('mailing', 'subject')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Template')}} <a wire:click="sort('mailing', 'email')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Size')}} <a wire:click="sort('mailing', 'size')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Interval')}} <a wire:click="sort('mailing', 'interval')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Sent emails')}}</th>
                                    <th>{{__('Created at')}} <a wire:click="sort('mailing', 'created_at')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($batches))
                                    @foreach($batches as $batch)
                                        <tr>
                                            <td>{{ $batch->subject }}</td>
                                            <td>{{ $batch->templates ? $batch->templates->name : '' }}</td>
                                            <td>{{ $batch->size }}</td>
                                            <td>{{ $batch->interval }}</td>
                                            <td>{{ $batch->sent }} / {{ $batch->not_sent }}</td>
                                            <td>{{ $batch->created_at }}</td>
                                            <td>
                                                @if(permission('mailing', 'update'))
                                                    <a class="blues" wire:click="modalEditBatch({{$batch->id}})" alt="{{__('Edit email')}}" title="{{__('Edit email')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif

                                                @if(permission('mailing', 'update'))
                                                    @if($batch->active)
                                                        <a class="reds" wire:click="confirmCancel({{$batch->id}})" alt="{{__('Cancel')}}" title="{{__('Cancel')}}"><span class="block"><i class="far fa-window-close"></i></span></a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have batches added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($batches))
                                {{ $batches->links() }}
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="addEmail, editEmail, sendEmail, table, sort, delete">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="addEmail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create email')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successEmail'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successEmail') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="name" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-label">{{__('Type')}}</label>
                                <input wire:model="type" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('type') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor"
                                         x-init="
                                         quill = new Quill($refs.quillEditor, {theme: 'snow'});
                                         quill.on('text-change', function () {
                                           $dispatch('input', quill.root.innerHTML);
                                           @this.set('description', quill.root.innerHTML)
                                         });
                                    "
                                         wire:model.lazy="description"
                                    >
                                        {!! $description !!}
                                    </div>
                                </div>
                                @error('description')<span class="error">{{ $message }}</span>@enderror
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
                        <div wire:loading wire:target="addEmail">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addEmail">
                            <button type="button" wire:click="addEmail" class="btn btn-primary">{{__('Save email')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editEmail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit email')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successEmail'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successEmail') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="name" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-label">{{__('Type')}}</label>
                                <input wire:model="type" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('type') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEditor"
                                         x-init="
                                     quill_data = '{{ $description }}';
                                     quill_edit = new Quill($refs.quillEditor, {theme: 'snow'});
                                     quill_edit.on('text-change', function () {
                                       $dispatch('input', quill_edit.root.innerHTML);
                                       @this.set('content', quill_edit.root.innerHTML)
                                     });
                                "
                                         wire:model.lazy="description"
                                    >
                                        {!! $description !!}
                                    </div>
                                </div>
                                @error('content')<span class="error">{{ $message }}</span>@enderror
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
                        <div wire:loading wire:target="editEmail">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editEmail">
                            <button type="button" wire:click="editEmail" class="btn btn-primary">{{__('Edit email')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="sendEmail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create email')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successEmail'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successEmail') }}
                                </div>
                            @endif
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label for="select2-customers" class="form-label">{{__('Clients')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <select wire:model="recipients" class="form-control" multiple="multiple" id="select2-customers" :errors="$errors" autocomplete="off">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="subject" class="form-label">{{__('Subject')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="subject" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('subject') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="batch" wire:model="batch">
                                <label class="form-check-label" for="batch">
                                    {{__('Email batches')}}
                                </label>
                            </div>
                            @if($batch)
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="batch_size" class="form-label">{{__('Batch size')}} ({{__('emails')}})</label>
                                            <input wire:model="batch_size" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                            @error('batch_size') <span class="error">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="batch_interval" class="form-label">{{__('Batch interval')}} ({{__('hours')}})</label>
                                            <input wire:model="batch_interval" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                            @error('batch_interval') <span class="error">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="sendEmail">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="sendEmail">
                            <button type="button" wire:click="sendEmail" class="btn btn-primary">{{__('Send email')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editBatch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg mw-800" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{__('Edit batch')}}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="inside-form">
                                @if(session()->has('successBatch'))
                                    <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        {{ session('successBatch') }}
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="name" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                    @error('name') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <div class="mb-3" wire:ignore>
                                        <div x-data
                                             x-ref="quillEditor"
                                             x-init="
                                                 quill_data = '{{ $description }}';
                                                 quill_batch = new Quill($refs.quillEditor, {theme: 'snow'});
                                                 quill_batch.on('text-change', function () {
                                                   $dispatch('input', quill_batch.root.innerHTML);
                                                   @this.set('description', quill_batch.root.innerHTML)
                                                 });
                                            "
                                             wire:model.lazy="description"
                                        >
                                            {!! $description !!}
                                        </div>
                                    </div>
                                    @error('description')<span class="error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="inside-form mt-1 pb-0">
                                <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div wire:loading wire:target="editBatch">
                                <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                            </div>
                            <div wire:loading.remove wire:target="editBatch">
                                <button type="button" wire:click="editBatch" class="btn btn-primary">{{__('Edit batch email')}}</button>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                        </div>
                    </div>
                </div>
            </div>

        <div wire:ignore.self class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Confirm cancel')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p>{{__('Are you sure want to cancel this mailing batch?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="cancel" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Cancel')}}</button>
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
                        <p>{{__('Are you sure want to delete this :item?', ['item' => $item])}}</p>
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
            $("#select2-customers").select2({ width: '100%' });

            $('#select2-customers').on('change', function (e) {
                let data = ($('#select2-customers').select2("val"));
                @this.set('recipients', data);
            });
        });

        window.addEventListener('resetCustomers', event => {
            $('#select2-customers').empty();
            $('#select2-customers').select2({data: event.detail.options, width: '100%'});

            // Prepend "groups"
            if(event.detail.groups !== undefined) {
                event.detail.groups.forEach( function(value, index) {
                    var newOption = new Option("{{__('Group')}}" + ': ' + value.name, "G:" + value.name, false, false);
                    $('#select2-customers').prepend(newOption).trigger('change');
                });
            }

            // Prepend "roles"
            if(event.detail.roles !== undefined) {
                event.detail.roles.forEach( function(value, index) {
                    var newOption = new Option("{{__('Role')}}" + ': ' + value.name, "R:" + value.name, false, false);
                    $('#select2-customers').prepend(newOption).trigger('change');
                });
            }

            // Prepend "all customers"
            var newOption = new Option(event.detail.all, 0, false, false);
            $('#select2-customers').prepend(newOption).trigger('change');
        });

        window.addEventListener('showAddEmail', event => {
            quill.container.firstChild.innerHTML = '';
            $('#addEmail').modal('show');
        });

        window.addEventListener('hideAddEmail', event => {
            $('#addEmail').modal('hide');
        });

        window.addEventListener('showEditEmail', event => {
            quill_edit.container.firstChild.innerHTML = event.detail.editor;
            $('#editEmail').modal('show');
        });

        window.addEventListener('showEditBatch', event => {
            quill_batch.container.firstChild.innerHTML = event.detail.editor;
            $('#editBatch').modal('show');
        });

        window.addEventListener('hideEditEmail', event => {
            $('#editEmail').modal('hide');
        });

        window.addEventListener('hideEditBatch', event => {
            $('#editBatch').modal('hide');
        });

        window.addEventListener('showSendEmail', event => {
            $('#sendEmail').modal('show');
        });

        window.addEventListener('hideSendEmail', event => {
            $('#sendEmail').modal('hide');
        });

        window.addEventListener('confirmCancel', event => {
            $('#cancelModal').modal('show');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('showError', event => {
            $('#errorModal').modal('show');
        });
    </script>
@endpush
