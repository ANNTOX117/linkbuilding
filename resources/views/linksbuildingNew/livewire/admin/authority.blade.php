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

        @if(permission('authorities', 'update'))
            <div class="dropdown float-right">
                <button class="add round btn-small dropdown-fh dropdown-toggle" type="button" id="columns" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-cog"></i>
                </button>
                <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="columns">
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.type" wire:change="toggleColumns($event.target.value)"> {{__('Type')}}
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.subnet" wire:change="toggleColumns($event.target.value)"> {{__('Subnet')}}
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.refering_domains" wire:change="toggleColumns($event.target.value)"> {{__('Refering domains')}}
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.pa" wire:change="toggleColumns($event.target.value)"> {{__('PA')}}
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.da" wire:change="toggleColumns($event.target.value)"> {{__('DA')}}
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.tf" wire:change="toggleColumns($event.target.value)"> {{__('TF')}}
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.cf" wire:change="toggleColumns($event.target.value)"> {{__('CF')}}
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.dre" wire:change="toggleColumns($event.target.value)"> {{__('DRE')}}
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.backlinks" wire:change="toggleColumns($event.target.value)"> {{__('Backlinks')}}
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.price" wire:change="toggleColumns($event.target.value)"> {{__('Price')}}
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="checkbox" wire:model="columns.price_special" wire:change="toggleColumns($event.target.value)"> {{__('Special price')}}
                        </label>
                    </li>
                </ul>
            </div>
        @endif

        <div class="topbar">
            <div class="left bold">
                @if(permission('authorities', 'create'))
                    <a wire:click="export"><span class="add round btn-small"><i class="fas fa-upload"></i> {{__('Export CSV')}}</span></a>
                    <a data-toggle="modal" wire:click="modalImport"><span class="add round btn-small reverse"><i class="fas fa-download"></i> {{__('Import CSV')}}</span></a>
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
                                <th>{{__('URL')}} <a wire:click="sort('url')"><i class="fas fa-sort"></i></a></th>
                                @if($columns['type'])<th>{{__('Type')}} <a wire:click="sort('type')"><i class="fas fa-sort"></i></a></th>@endif
                                @if($columns['subnet'])<th>{{__('Subnet')}} <a wire:click="sort('subnet')"><i class="fas fa-sort"></i></a></th>@endif
                                @if($columns['refering_domains'])<th>{{__('Refering domains')}} <a wire:click="sort('subnet')"><i class="fas fa-sort"></i></a></th>@endif
                                @if($columns['pa'])<th>{{__('PA')}} <a wire:click="sort('pa')"><i class="fas fa-sort"></i></a></th>@endif
                                @if($columns['da'])<th>{{__('DA')}} <a wire:click="sort('da')"><i class="fas fa-sort"></i></a></th>@endif
                                @if($columns['tf'])<th>{{__('TF')}} <a wire:click="sort('tf')"><i class="fas fa-sort"></i></a></th>@endif
                                @if($columns['cf'])<th>{{__('CF')}} <a wire:click="sort('cf')"><i class="fas fa-sort"></i></a></th>@endif
                                @if($columns['dre'])<th>{{__('DRE')}} <a wire:click="sort('cf')"><i class="fas fa-sort"></i></a></th>@endif
                                @if($columns['backlinks'])<th>{{__('Backlinks')}} <a wire:click="sort('backlinks')"><i class="fas fa-sort"></i></a></th>@endif
                                @if($columns['price'])<th>{{__('Price')}} <a wire:click="sort('price')"><i class="fas fa-sort"></i></a></th>@endif
                                @if($columns['price_special'])<th>{{__('Special price')}} <a wire:click="sort('price_special')"><i class="fas fa-sort"></i></a></th>@endif
                                <th>{{__('Featured')}}</th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($sites))
                                @foreach($sites as $site)
                                    <tr>
                                        <td>@if(!empty($site->wordpress)) <i class="fab fa-wordpress text-ghost mr-1"></i> @else <i class="fas fa-globe text-ghost mr-1"></i> @endif {{ $site->url }}</td>
                                        @if($columns['type'])
                                            <td>{{ type_page($site->type) }}</td>
                                        @endif
                                        @if($columns['subnet'])
                                            <td>
                                                @if($edit and $edit_id == $site->id)
                                                    <input wire:model="subnet" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('subnet') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $site->subnet ?? '-' }}
                                                @endif
                                            </td>
                                        @endif
                                        @if($columns['refering_domains'])
                                            <td>
                                                @if($edit and $edit_id == $site->id)
                                                    <input wire:model="refering_domains" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('refering_domains') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $site->refering_domains ?? '0' }}
                                                @endif
                                            </td>
                                        @endif
                                        @if($columns['pa'])
                                            <td>
                                                @if($edit and $edit_id == $site->id)
                                                    <input wire:model="pa" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('pa') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $site->pa ?? '0' }}
                                                @endif
                                            </td>
                                        @endif
                                        @if($columns['da'])
                                            <td>
                                                @if($edit and $edit_id == $site->id)
                                                    <input wire:model="da" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('da') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $site->da ?? '0' }}
                                                @endif
                                            </td>
                                        @endif
                                        @if($columns['tf'])
                                            <td>
                                                @if($edit and $edit_id == $site->id)
                                                    <input wire:model="tf" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('tf') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $site->tf ?? '0' }}
                                                @endif
                                            </td>
                                        @endif
                                        @if($columns['cf'])
                                            <td>
                                                @if($edit and $edit_id == $site->id)
                                                    <input wire:model="cf" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('cf') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $site->cf ?? '0' }}
                                                @endif
                                            </td>
                                        @endif
                                        @if($columns['dre'])
                                            <td>
                                                @if($edit and $edit_id == $site->id)
                                                    <input wire:model="dre" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('dre') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $site->dre ?? '0' }}
                                                @endif
                                            </td>
                                        @endif
                                        @if($columns['backlinks'])
                                            <td>
                                                @if($edit and $edit_id == $site->id)
                                                    <div wire:ignore>
                                                        <input id="tags" wire:model="backlinks" wire:change="changeBacklinks($event.target.value)" type="text" class="form-control w-backlinks" :errors="$errors" autocomplete="off" />
                                                        @error('backlinks') <span class="error">{{ $message }}</span> @enderror
                                                    </div>
                                                @else
                                                    {!! replace_comma_with_br($site->backlinks) ?? '-' !!}
                                                @endif
                                            </td>
                                        @endif
                                        @if($columns['price'])
                                            <td>
                                                @if($edit and $edit_id == $site->id)
                                                    <input wire:model="price" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('price') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ currency() }} {{ get_price($site->price ?? '0.00') }}
                                                @endif
                                            </td>
                                        @endif
                                        @if($columns['price_special'])
                                            <td>
                                                @if($edit and $edit_id == $site->id)
                                                    <input wire:model="price_special" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('price_special') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ currency() }} {{ get_price($site->price_special ?? '0.00') }}
                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                            @if(permission('authorities', 'update'))
                                                <div class="form-check d-initial"><input wire:model="status.{{$site->id}}" wire:change="changeStatus({{$site->id}})" type="checkbox" class="form-check-input" /></div>
                                            @endif
                                        </td>
                                        <td>
                                            @if(permission('authorities', 'update'))
                                                @if($edit and $edit_id == $site->id)
                                                    <a class="blues" wire:click="saveRow({{$site->id}})" alt="{{__('Save')}}" title="{{__('Save')}}"><span class="block"><i class="far fa-save"></i></span></a>
                                                    <a class="reds" wire:click="cancelRow" alt="{{__('Cancel')}}" title="{{__('Cancel')}}"><span class="block"><i class="fas fa-times"></i></span></a>
                                                @else
                                                    <a class="blues" wire:click="editRow({{$site->id}})" alt="{{__('Edit site')}}" title="{{__('Edit site')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="14">
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

        <div wire:loading wire:target="editSite, importCSV, sort, columns, toggleColumns">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Import CSV')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successImport'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successImport') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="logo">{{__('CSV')}}</label>
                                <input type="file" class="form-control-file @error('csv') is_error @enderror" id="csv" wire:model.lazy="csv" />
                                @error('csv')<span class="error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="importCSV">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="importCSV">
                            <button type="button" wire:click="importCSV" class="btn btn-primary">{{__('Import CSV')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            //
        });

        window.addEventListener('showImport', event => {
            //$('#import').modal('show');
            $('#import').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideImport', event => {
            $('#import').modal('hide');
        });

        window.addEventListener('doDownload', event => {
            window.open('{{ route('download_sites') }}', '_blank');
        });

        window.addEventListener('resetTags', event => {
            $('#tags').tagsinput({
                'data': event.detail.tags,
                'removeWithBackspace': true,
                'delimiter': [','],
                'onChange': function () {
                    @this.set('backlinks', $('#tags').val());
                }
            });
        });

        $(document).on('change', '#tags', function() {
            @this.set('backlinks', $('#tags').val());
        });
    </script>
@endpush
