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
                @if(permission('taxes', 'create'))
                    <a data-toggle="modal" wire:click="modalAddTax"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add tax')}}</span></a>
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
                        <table class="table table-hover table-taxes">
                            <thead>
                            <tr>
                                <th>{{__('Country')}} <a wire:click="sort('country')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Tax')}} <a wire:click="sort('tax')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($taxes))
                                @foreach($taxes as $item)
                                    <tr>
                                        <td>{{ $item->country }}</td>
                                        <td>{{ integer_or_float($item->tax) }}%</td>
                                        <td>
                                            @if(permission('taxes', 'update'))
                                                <a class="blues" wire:click="modalEditTax({{$item->id}})" alt="{{__('Edit tax')}}" title="{{__('Edit tax')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                            @endif
                                            @if(permission('taxes', 'delete'))
                                                <a class="reds" wire:click="confirm({{$item->id}})" alt="{{__('Delete tax')}}" title="{{__('Delete tax')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">
                                        <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have taxes added yet')}}</em></div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        @if(!empty($taxes))
                            {{ $taxes->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="addTax, editTax, sort, delete">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="addTax" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create tax')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successTax'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successTax') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="country" class="form-label">{{__('Country')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select id="country" class="form-control" :errors="$errors" wire:ignore>
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $option)
                                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('country') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="tax" class="form-label">{{__('Tax')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="tax" type="number" min="0" class="form-control" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                @error('tax') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addTax">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addTax">
                            <button type="button" wire:click="addTax" class="btn btn-primary">{{__('Save tax')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editTax" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit tax')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successTax'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successTax') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="country_edit" class="form-label">{{__('Country')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select id="country_edit" class="form-control" :errors="$errors" wire:ignore>
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $option)
                                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('country') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="tax" class="form-label">{{__('Tax')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="tax" type="number" min="0" class="form-control" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                @error('tax') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editTax">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editTax">
                            <button type="button" wire:click="editTax" class="btn btn-primary">{{__('Edit tax')}}</button>
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
                        <p>{{__('Are you sure want to delete this tax?')}}</p>
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
        document.addEventListener('livewire:load', function () {
            $(document).on('change', '#country', function() {
                @this.set('country', $(this).val());
            });

            $(document).on('change', '#country_edit', function() {
                @this.set('country', $(this).val());
            });
        });

        window.addEventListener('showAddTax', event => {
            $('#country').prop('selectedIndex', 0);
            $('#addTax').modal('show');
        });

        window.addEventListener('hideAddTax', event => {
            $('#addTax').modal('hide');
        });

        window.addEventListener('showEditTax', event => {
            $('#editTax').modal('show');
        });

        window.addEventListener('hideEditTax', event => {
            $('#editTax').modal('hide');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('selectCountry', event => {
            $('#country_edit').val(event.detail.country).trigger('change');
        });
    </script>
@endpush
