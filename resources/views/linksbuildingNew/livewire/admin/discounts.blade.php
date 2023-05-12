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
                @if(permission('discounts', 'create'))
                    @if($tab == 'staffels') <a data-toggle="modal" wire:click="modalAddStaffel"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add staffel')}}</span></a> @endif
                    @if($tab == 'rules') <a data-toggle="modal" wire:click="modalAddRule"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add rule')}}</span></a> @endif
                    @if($tab == 'defaults') <a data-toggle="modal" wire:click="modalAddDefault"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add default by year')}}</span></a> @endif
                    @if($tab == 'by_price') <a data-toggle="modal" wire:click="modalAddDefaultPrice"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add default by price')}}</span></a> @endif
                @endif
            </div>
        </div>

        <div class="cont ">
            <div class="card">
                <div class="card-body">
                    <div class="tab">
                        <a @if($tab == 'staffels') class="active" @endif wire:click="table('staffels')">{{__('Staffel') }}</a>
                        <a @if($tab == 'rules') class="active" @endif wire:click="table('rules')">{{__('Rules')}}</a>
                        <a @if($tab == 'defaults') class="active" @endif wire:click="table('defaults')">{{__('By years')}}</a>
                        <a @if($tab == 'by_price') class="active" @endif wire:click="table('by_price')">{{__('By price')}}</a>
                    </div>
                    <div class="table-responsive">

                        @if($tab == 'staffels')
                            <table class="table table-hover table-discounts">
                                <thead>
                                <tr>
                                    <th>{{__('Name')}} <a wire:click="sort('staffels', 'name')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($staffels))
                                    @foreach($staffels as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <a class="blues" wire:click="modalOpenStaffel({{$item->id}})" alt="{{__('View discounts')}}" title="{{__('View discounts')}}"><span class="block"><i class="far fa-eye"></i></span></a>
                                                @if(permission('discounts', 'update'))
                                                    <a class="blues" wire:click="modalEditStaffel({{$item->id}})" alt="{{__('Edit group')}}" title="{{__('Edit group')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('discounts', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$item->id}})" alt="{{__('Delete group')}}" title="{{__('Delete group')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have discounts added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($staffels))
                                {{ $staffels->links() }}
                            @endif
                        @endif

                        @if($tab == 'rules')
                            <table class="table table-hover table-rules">
                                <thead>
                                <tr>
                                    <th>{{__('Discount')}} <a wire:click="sort('rules_discounts', 'discount')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Rule')}}</th>
                                    <th>{{__('Active')}} <a wire:click="sort('rules_discounts', 'active')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Price')}} <a wire:click="sort('rules_discounts', 'price')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Percentage')}} <a wire:click="sort('rules_discounts', 'percentage')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($ruleslist))
                                    @foreach($ruleslist as $item)
                                        <tr>
                                            <td>{{ $item->discounts ? $item->discounts->name : '' }}</td>
                                            <td>{{ get_rule($item) }}</td>
                                            <td>
                                                @if(permission('discounts', 'update'))
                                                    <div class="form-check d-initial"><input wire:model="status.{{$item->id}}" wire:change="changeStatus({{$item->id}})" type="checkbox" class="form-check-input" /></div>
                                                @endif
                                            </td>
                                            <td>{{ currency() }} {{ get_price($item->price) }}</td>
                                            <td>{{ integer_or_float($item->percentage) }}%</td>
                                            <td>
                                                @if(permission('discounts', 'update'))
                                                    <a class="blues" wire:click="modalEditRule({{$item->id}})" alt="{{__('Edit rule')}}" title="{{__('Edit rule')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('discounts', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$item->id}})" alt="{{__('Delete rule')}}" title="{{__('Delete rule')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have rules added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($ruleslist))
                                {{ $ruleslist->links() }}
                            @endif
                        @endif

                        @if($tab == 'defaults')
                            <table class="table table-hover table-rules">
                                <thead>
                                <tr>
                                    <th>{{__('Years')}}</th>
                                    <th>{{__('Percentage')}}</th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($discounts_default))
                                    @foreach($discounts_default as $default)
                                        <tr>
                                            <td>
                                                {{ $default->years ?? '0' }}
                                            </td>
                                            <td>
                                                {{ integer_or_float($default->percentage) ?? '0' }}%
                                            </td>
                                            <td>
                                                @if(permission('discounts', 'update'))
                                                    <a class="blues" wire:click="editDefaultRow({{$default->id}})" alt="{{__('Edit default discount')}}" title="{{__('Edit default discount')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('discounts', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$default->id}})" alt="{{__('Delete default discount')}}" title="{{__('Delete default discount')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have default discounts added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        @endif

                        @if($tab == 'by_price')
                            <table class="table table-hover table-rules">
                                <thead>
                                <tr>
                                    <th>{{__('Price')}}</th>
                                    <th>{{__('Discount')}}</th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($discounts_price))
                                    @foreach($discounts_price as $default_by_price)
                                        <tr>
                                            <td>
                                                {{ currency() }} {{ $default_by_price->price ?? '0' }}
                                            </td>
                                            <td>
                                                {{ integer_or_float($default_by_price->percentage) ?? '0' }}%
                                            </td>
                                            <td>
                                                @if(permission('discounts', 'update'))
                                                    <a class="blues" wire:click="editDefaultPriceRow({{$default_by_price->id}})" alt="{{__('Edit price discount')}}" title="{{__('Edit price discount')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('discounts', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$default_by_price->id}})" alt="{{__('Delete price discount')}}" title="{{__('Delete price discount')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have price discounts added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="addStaffel, editStaffel, addRule, editRule, openStaffel, addDefault, editDefault, addDefaultPrice, editDefaultPrice, table, sort, delete">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="openStaffel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-600" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $group }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">

                            <table class="table table-hover table-links">
                                <thead>
                                    <tr>
                                        <th>{{__('From')}}</th>
                                        <th>{{__('To')}}</th>
                                        <th>{{__('Percentage')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($from) and $open)
                                        @foreach($from as $key => $value)
                                            <tr>
                                                <td>@if(empty($to[$key])) > @endif {{ $from[$key] }} {{ plural_or_singular('item', $from[$key]) }}</td>
                                                <td>@if(!empty($to[$key])){{ $to[$key] }} {{ plural_or_singular('item', $to[$key]) }} @else - @endif</td>
                                                <td>{{ integer_or_float($percentage[$key]) }}%</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">{{__('OK')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addStaffel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-600" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create staffel')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successStaffel'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successStaffel') }}
                                </div>
                            @endif
                            @if(session()->has('errorStaffel'))
                                <div class="alert alert-danger mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('errorStaffel') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="group" class="form-label">{{__('Group name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="group" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('group') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="add-discount">
                                <div class="row mt-4">
                                    <div class="col">
                                        <label for="from" class="form-label">{{__('From')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                        <input wire:model="from.0" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                        @error('from.0') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col">
                                        <label for="to" class="form-label">{{__('To')}} @if(!$more)<sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup>@endif</label>
                                        <input wire:model="to.0" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                        @error('to.0') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col">
                                        <label for="percentage" class="form-label">{{__('Percentage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                        <input wire:model="percentage.0" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                        @error('percentage.0') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col">
                                        <button class="btn text-white btn-success btn-sm mt-30" wire:click.prevent="add({{$i}})"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            @foreach($discounts as $key => $value)
                                <div class="add-discount">
                                    <div class="row mt-4">
                                        <div class="col">
                                            <label for="from" class="form-label">{{__('From')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                            <input wire:model="from.{{ $value }}" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                            @error('from.'.$value) <span class="error">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col">
                                            <label for="to" class="form-label">{{__('To')}} @if(!$more)<sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup>@endif</label>
                                            <input wire:model="to.{{ $value }}" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                            @error('to.'.$value) <span class="error">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col">
                                            <label for="percentage" class="form-label">{{__('Percentage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                            <input wire:model="percentage.{{ $value }}" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                            @error('percentage.'.$value) <span class="error">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-danger btn-sm mt-30" wire:click.prevent="remove({{$key}})"><i class="far fa-trash-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addStaffel">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addStaffel">
                            <button type="button" wire:click="addStaffel" class="btn btn-primary">{{__('Save staffel')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editStaffel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-600" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit staffel')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successStaffel'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successStaffel') }}
                                </div>
                            @endif
                            @if(session()->has('errorStaffel'))
                                <div class="alert alert-danger mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('errorStaffel') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="group" class="form-label">{{__('Group name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="group" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                @error('group') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            @if(!empty($from))
                                @foreach($from as $key => $value)
                                    @if(isset($from[$key]))
                                        <div class="add-discount">
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <label for="from" class="form-label">{{__('From')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                                    <input wire:model="from.{{ $key }}" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                                    @error('from.'.$key) <span class="error">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="col">
                                                    <label for="to" class="form-label">{{__('To')}} @if(!$more)<sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup>@endif</label>
                                                    <input wire:model="to.{{ $key }}" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                                    @error('to.'.$key) <span class="error">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="col">
                                                    <label for="percentage" class="form-label">{{__('Percentage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                                    <input wire:model="percentage.{{ $key }}" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                                    @error('percentage.'.$key) <span class="error">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="col">
                                                    @if($loop->first)
                                                        <button class="btn text-white btn-success btn-sm mt-30" wire:click.prevent="add({{$i}}, true)"><i class="fas fa-plus"></i></button>
                                                    @else
                                                        <button class="btn btn-danger btn-sm mt-30" wire:click.prevent="remove({{$key}}, true)"><i class="far fa-trash-alt"></i></button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editStaffel">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editStaffel">
                            <button type="button" wire:click="editStaffel" class="btn btn-primary">{{__('Edit staffel')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addRule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create rule')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successRule'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successRule') }}
                                </div>
                            @endif
                            @if(session()->has('errorRule'))
                                <div class="alert alert-danger mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('errorRule') }}
                                </div>
                            @endif
                            <div class="row mt-4">
                                <div class="col">
                                    <label for="rule_staffel" class="form-label">{{__('Staffel')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <select wire:model="rule_staffel" id="rule_staffel" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose an option')}}</option>
                                        @if($staffels->isNotEmpty())
                                            @foreach($staffels as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('rule_staffel') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="rule_price" class="form-label">{{__('Price')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="rule_price" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('rule_price') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="rule_percentage" class="form-label">{{__('Percentage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="rule_percentage" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('percentage') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <label for="rule_user" class="form-label">{{__('User or Group or Product')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <select wire:model="rule_user" id="rule_user" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose an user')}}</option>
                                        @if($users->isNotEmpty())
                                            @foreach($users as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }} {{ $item->lastname }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('rule_user') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="rule_group" class="form-label">&nbsp;</label>
                                    <select wire:model="rule_group" id="rule_group" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose a group')}}</option>
                                        @if($groups->isNotEmpty())
                                            @foreach($groups as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('rule_group') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="rule_product" class="form-label">&nbsp;</label>
                                    <select wire:model="rule_product" id="rule_product" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose a product')}}</option>
                                        <option value="startingpage">{{__('Starting page')}}</option>
                                        <option value="homepagelink">{{__('Homepagelink')}}</option>
                                        <option value="childstartingpage">{{__('Child starting page')}}</option>
                                        <option value="intext">{{__('Intext')}}</option>
                                        <option value="blogs">{{__('Blogs')}}</option>
                                        <option value="packages">{{__('Packages')}}</option>
                                    </select>
                                    @error('rule_product') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rule_active" wire:model="rule_active">
                                <label class="form-check-label" for="rule_active">
                                    {{__('Active')}}
                                </label>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addRule">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addRule">
                            <button type="button" wire:click="addRule" class="btn btn-primary">{{__('Save rule')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editRule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit rule')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successRule'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successRule') }}
                                </div>
                            @endif
                            @if(session()->has('errorRule'))
                                <div class="alert alert-danger mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('errorRule') }}
                                </div>
                            @endif
                            <div class="row mt-4">
                                <div class="col">
                                    <label for="rule_staffel_edit" class="form-label">{{__('Staffel')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <select wire:model="rule_staffel" id="rule_staffel_edit" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose an option')}}</option>
                                        @if($staffels->isNotEmpty())
                                            @foreach($staffels as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('rule_staffel') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="rule_price" class="form-label">{{__('Price')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="rule_price" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('rule_price') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="rule_percentage" class="form-label">{{__('Percentage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="rule_percentage" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('percentage') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <label for="rule_user_edit" class="form-label">{{__('User or Group or Product')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <select wire:model="rule_user" id="rule_user_edit" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose an user')}}</option>
                                        @if($users->isNotEmpty())
                                            @foreach($users as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }} {{ $item->lastname }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('rule_user') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="rule_group_edit" class="form-label">&nbsp;</label>
                                    <select wire:model="rule_group" id="rule_group_edit" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose a group')}}</option>
                                        @if($groups->isNotEmpty())
                                            @foreach($groups as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('rule_group') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="rule_product_edit" class="form-label">&nbsp;</label>
                                    <select wire:model="rule_product" id="rule_product_edit" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose a product')}}</option>
                                        <option value="startingpage">{{__('Starting page')}}</option>
                                        <option value="homepagelink">{{__('Homepagelink')}}</option>
                                        <option value="childstartingpage">{{__('Child starting page')}}</option>
                                        <option value="intext">{{__('Intext')}}</option>
                                        <option value="blogs">{{__('Blogs')}}</option>
                                        <option value="packages">{{__('Packages')}}</option>
                                    </select>
                                    @error('rule_product') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rule_active" wire:model="rule_active">
                                <label class="form-check-label" for="rule_active">
                                    {{__('Active')}}
                                </label>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editRule">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editRule">
                            <button type="button" wire:click="editRule" class="btn btn-primary">{{__('Update rule')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addDefault" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create default by year')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successDefault'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successDefault') }}
                                </div>
                            @endif
                            @if(session()->has('errorDefault'))
                                <div class="alert alert-danger mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('errorDefault') }}
                                </div>
                            @endif
                            <div class="row mt-4">
                                <div class="col">
                                    <label for="default_years" class="form-label">{{__('Years')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="default_years" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('default_years') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="default_percentage" class="form-label">{{__('Percentage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="default_percentage" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('default_percentage') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addDefault">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addDefault">
                            <button type="button" wire:click="addDefault" class="btn btn-primary">{{__('Save default')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editDefault" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit default by year')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successDefault'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successDefault') }}
                                </div>
                            @endif
                            @if(session()->has('errorDefault'))
                                <div class="alert alert-danger mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('errorDefault') }}
                                </div>
                            @endif
                            <div class="row mt-4">
                                <div class="col">
                                    <label for="default_years" class="form-label">{{__('Years')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="default_years" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('default_years') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="default_percentage" class="form-label">{{__('Percentage')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="default_percentage" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('default_percentage') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editDefault">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editDefault">
                            <button type="button" wire:click="editDefault" class="btn btn-primary">{{__('Edit default')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addDefaultPrice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create default by price')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successDefaultPrice'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successDefaultPrice') }}
                                </div>
                            @endif
                            @if(session()->has('errorDefaultPrice'))
                                <div class="alert alert-danger mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('errorDefaultPrice') }}
                                </div>
                            @endif
                            <div class="row mt-4">
                                <div class="col">
                                    <label for="default_price_minimum" class="form-label">{{__('Minimum price')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="default_price_minimum" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('default_price_minimum') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="default_price_percentage" class="form-label">{{__('Discount')}} %<sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="default_price_percentage" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('default_price_percentage') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addDefaultPrice">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addDefaultPrice">
                            <button type="button" wire:click="addDefaultPrice" class="btn btn-primary">{{__('Save discount')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editDefaultPrice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit default by price')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successDefaultPrice'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successDefaultPrice') }}
                                </div>
                            @endif
                            @if(session()->has('errorDefaultPrice'))
                                <div class="alert alert-danger mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('errorDefaultPrice') }}
                                </div>
                            @endif
                            <div class="row mt-4">
                                <div class="col">
                                    <label for="default_price_minimum" class="form-label">{{__('Minimum price')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="default_price_minimum" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('default_price_minimum') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="default_price_percentage" class="form-label">{{__('Discount')}} %<sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="default_price_percentage" type="number" class="form-control" min="1" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                    @error('default_price_percentage') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editDefaultPrice">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editDefaultPrice">
                            <button type="button" wire:click="editDefaultPrice" class="btn btn-primary">{{__('Edit discount')}}</button>
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
        document.addEventListener('livewire:load', function () {
            //
        });

        window.addEventListener('showAddStaffel', event => {
            //$('#addStaffel').modal('show');
            $('#addStaffel').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideAddStaffel', event => {
            $('#addStaffel').modal('hide');
        });

        window.addEventListener('showEditStaffel', event => {
            //$('#editStaffel').modal('show');
            $('#editStaffel').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideEditStaffel', event => {
            $('#editStaffel').modal('hide');
        });

        window.addEventListener('showAddRule', event => {
            //$('#addRule').modal('show');
            $('#addRule').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideAddRule', event => {
            $('#addRule').modal('hide');
        });

        window.addEventListener('showAddDefault', event => {
            //$('#addDefault').modal('show');
            $('#addDefault').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideAddDefault', event => {
            $('#addDefault').modal('hide');
        });

        window.addEventListener('showAddDefaultPrice', event => {
            //$('#addDefaultPrice').modal('show');
            $('#addDefaultPrice').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideAddDefaultPrice', event => {
            $('#addDefaultPrice').modal('hide');
        });

        window.addEventListener('showEditRule', event => {
            //$('#editRule').modal('show');
            $('#editRule').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideEditRule', event => {
            $('#editRule').modal('hide');
        });

        window.addEventListener('showOpenStaffel', event => {
            //$('#openStaffel').modal('show');
            $('#openStaffel').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('showEditDefault', event => {
            //$('#editDefault').modal('show');
            $('#editDefault').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideEditDefault', event => {
            $('#editDefault').modal('hide');
        });

        window.addEventListener('showEditDefaultPrice', event => {
            //$('#editDefaultPrice').modal('show');
            $('#editDefaultPrice').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('hideEditDefaultPrice', event => {
            $('#editDefaultPrice').modal('hide');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('enabled_users', event => {
            $('#rule_user, #rule_user_edit, #rule_group, #rule_group_edit, #rule_product, #rule_product_edit').removeClass('select-disabled');
            $('#rule_group, #rule_group_edit, #rule_product, #rule_product_edit').addClass('select-disabled').prop('selectedIndex', 0);
        });

        window.addEventListener('enabled_groups', event => {
            $('#rule_user, #rule_user_edit, #rule_group, #rule_group_edit, #rule_product, #rule_product_edit').removeClass('select-disabled');
            $('#rule_user, #rule_user_edit, #rule_product, #rule_product_edit').addClass('select-disabled').prop('selectedIndex', 0);
        });

        window.addEventListener('enabled_products', event => {
            $('#rule_user, #rule_user_edit, #rule_group, #rule_group_edit, #rule_product, #rule_product_edit').removeClass('select-disabled');
            $('#rule_user, #rule_user_edit, #rule_group, #rule_group_edit').addClass('select-disabled').prop('selectedIndex', 0);
        });

        $("#openStaffel").on("hidden.bs.modal", function () {
            @this.set('open', false);
        });

        $(document).on('change', '#rule_product', function() {
            @this.set('rule_product', $('#rule_product').val());
        });
    </script>
@endpush
