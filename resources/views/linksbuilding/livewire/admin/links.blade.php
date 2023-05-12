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
                @if(permission('links', 'create'))
                    <a data-toggle="modal" wire:click="modalAddLink"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add link')}}</span></a>
                @endif
            </div>
        </div>

        <div class="cont ">
            <div class="card">
                <div class="card-body">
                    <div class="tab">
                        <a @if($tab == 'links') class="active" @endif wire:click="table('links')">{{__('Links') }}</a>
                        <a @if($tab == 'requests') class="active" @endif wire:click="table('requests')">{{__('Requested links')}}</a>
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

                        @if($tab == 'links')
                            <table class="table table-hover table-links">
                                <thead>
                                <tr>
                                    <th>{{__('URL')}} <a wire:click="sort('links', 'links.url')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Site')}} <a wire:click="sort('links', 'authority_sites.url')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Text')}} <a wire:click="sort('links', 'alt')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Client')}} <a wire:click="sort('links', 'client')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Follow')}} <a wire:click="sort('links', 'follow')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('New tab')}} <a wire:click="sort('links', 'blank')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Active')}} <a wire:click="sort('links', 'active')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($links))
                                    @foreach($links as $link)
                                        <tr>
                                            <td>{{ $link->url }}</td>
                                            <td>
                                                {{ $link->authority_sites ? $link->authority_sites->url : '' }}
                                                @if(intval($link->error) == 1)
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="{{__('Error 404')}}">
                                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $link->alt }}</td>
                                            <td>{{ $link->clients ? $link->clients->name : '' }} {{ $link->clients ? $link->clients->lastname : '' }}</td>
                                            <td>@if($link->follow) <i class="fas fa-check-circle text-success"></i> @else <i class="fas fa-times-circle text-danger"></i> @endif</td>
                                            <td>@if(intval($link->blank) == 1) <i class="fas fa-check-circle text-success"></i> @else <i class="fas fa-times-circle text-danger"></i> @endif</td>
                                            <td>@if(intval($link->active) == 1) <i class="fas fa-check-circle text-success"></i> @elseif(intval($link->active) == 2) <i class="far fa-clock text-muted"></i> @else <i class="fas fa-times-circle text-danger"></i> @endif</td>
                                            <td>
                                                @if(permission('links', 'update'))
                                                    <a class="blues" wire:click="modalEditLink({{$link->id}})" alt="{{__('Edit link')}}" title="{{__('Edit link')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('links', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$link->id}})" alt="{{__('Delete link')}}" title="{{__('Delete link')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have links added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($links))
                                {{ $links->links() }}
                            @endif
                        @endif

                        @if($tab == 'requests')
                            <table class="table table-hover table-requests">
                                <thead>
                                <tr>
                                    <th>{{__('URL')}} <a wire:click="sort('requests', 'links.url')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Site')}} <a wire:click="sort('requests', 'authority_sites.url')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Text')}} <a wire:click="sort('requests', 'alt')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Client')}} <a wire:click="sort('requests', 'client')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($requests->isNotEmpty())
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>{{ $request->url }}</td>
                                            <td>{{ $request->authority_sites ? $request->authority_sites->url : '' }}</td>
                                            <td>{{ $request->alt }}</td>
                                            <td>{{ $request->clients ? $request->clients->name : '' }} {{ $request->clients ? $request->clients->lastname : '' }}</td>
                                            <td>
                                                @if(permission('links', 'update'))
                                                    <a class="blues" wire:click="confirmApprove({{$request->id}})" alt="{{__('Approve link')}}" title="{{__('Approve link')}}"><span class="block"><i class="fas fa-check"></i></span></a>
                                                    <a class="blues" wire:click="modalEditLink({{$request->id}})" alt="{{__('Edit link')}}" title="{{__('Edit link')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('links', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$request->id}})" alt="{{__('Delete link')}}" title="{{__('Delete link')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have requested links added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($requests))
                                {{ $requests->links() }}
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="addLink, editLink, table, sort, delete, approve">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="addLink" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create link')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successLink'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successLink') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="client" class="form-label">{{__('Client')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="client" id="client" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($clients))
                                        @foreach($clients as $option)
                                            <option value="{{ $option->id }}">{{ $option->name }} {{ $option->lastname }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('client') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="site" class="form-label">{{__('Site')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="site" id="site" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($sites))
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}">{{ $site->url }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('site') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="url" class="form-label">{{__('URL')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="url" type="url" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('url') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="anchor" class="form-label">{{__('Anchor')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="anchor" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('anchor') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <textarea wire:model="description" class="form-control" maxlength="250" :errors="$errors"></textarea>
                                @error('description') <span class="error">{{ $message }}</span> @enderror
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
                            <div class="row">
                                <div class="col">
                                    <label for="visible_at" class="form-label">{{__('Start date')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input id="visible_at" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                    @error('visible_at') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="ends_at" class="form-label">{{__('End date')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input id="ends_at" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                    @error('ends_at') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check">
                                        <input name="follow" class="form-check-input" type="radio" id="dofollow" value="follow" wire:model="follow">
                                        <label class="form-check-label" for="dofollow">{{__('Follow')}}</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input name="follow" class="form-check-input" type="radio" id="nofollow" value="nofollow" wire:model="follow">
                                        <label class="form-check-label" for="nofollow">{{__('Not follow')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check">
                                        <input name="blank" class="form-check-input" type="radio" id="doself" value="_self" wire:model="blank">
                                        <label class="form-check-label" for="doself">{{__('Same tab')}}</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input name="blank" class="form-check-input" type="radio" id="doblank" value="_blank" wire:model="blank">
                                        <label class="form-check-label" for="doblank">{{__('New tab')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="active" wire:model="active">
                                <label class="form-check-label" for="active">
                                    {{__('Active')}}
                                </label>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addLink">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addLink">
                            <button type="button" wire:click="addLink" class="btn btn-primary">{{__('Save link')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editLink" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit link')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successLink'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successLink') }}
                                </div>
                            @endif
                            <input type="hidden" wire:model="link" />
                            <div class="form-group">
                                <label for="client_edit" class="form-label">{{__('Client')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="client" id="client_edit" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($clients))
                                        @foreach($clients as $option)
                                            <option value="{{ $option->id }}">{{ $option->name }} {{ $option->lastname }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('client') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="site_edit" class="form-label">{{__('Site')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="site" id="site_edit" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($sites))
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}">{{ $site->url }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('site') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="url" class="form-label">{{__('URL')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="url" type="url" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('url') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="anchor" class="form-label">{{__('Anchor')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="anchor" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('anchor') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <textarea wire:model="description" class="form-control" maxlength="250" :errors="$errors"></textarea>
                                @error('description') <span class="error">{{ $message }}</span> @enderror
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
                            <div class="row">
                                <div class="col">
                                    <label for="visible_at" class="form-label">{{__('Start date')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input id="edit_visible_at" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                    @error('visible_at') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="ends_at" class="form-label">{{__('End date')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input id="edit_ends_at" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                    @error('ends_at') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check">
                                        <input name="follow_edit" class="form-check-input" type="radio" id="dofollow_edit" value="follow" wire:model="follow">
                                        <label class="form-check-label" for="dofollow_edit">{{__('Follow')}}</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input name="follow_edit" class="form-check-input" type="radio" id="nofollow_edit" value="nofollow" wire:model="follow">
                                        <label class="form-check-label" for="nofollow_edit">{{__('Not follow')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check">
                                        <input name="blank_edit" class="form-check-input" type="radio" id="doself_edit" value="_self" wire:model="blank">
                                        <label class="form-check-label" for="doself_edit">{{__('Same tab')}}</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input name="blank_edit" class="form-check-input" type="radio" id="doblank_edit" value="_blank" wire:model="blank">
                                        <label class="form-check-label" for="doblank_edit">{{__('New tab')}}</label>
                                    </div>
                                </div>
                            </div>
                            @if($edit)
                                <input type="hidden" wire:model="active" />
                            @else
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="active" wire:model="active">
                                    <label class="form-check-label" for="active">
                                        {{__('Active')}}
                                    </label>
                                </div>
                            @endif
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editLink">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editLink">
                            <button type="button" wire:click="editLink" class="btn btn-primary">{{__('Edit link')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Confirm approve')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p>{{__('Are you sure want to approve this requested link?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="approve" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Approve')}}</button>
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
                        <p>{{__('Are you sure want to delete this link?')}}</p>
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
            $('#visible_at').datepicker({
                dateFormat: "yy/mm/dd",
                autoclose: true,
                todayHighlight: true,
            }).on('change', function(e){
                @this.set('visible_at', e.target.value);
            });

            $('#ends_at').datepicker({
                dateFormat: "yy/mm/dd",
                autoclose: true,
                todayHighlight: true,
            }).on('change', function(e){
                @this.set('ends_at', e.target.value);
            });

            $('#edit_visible_at').datepicker({
                dateFormat: "yy/mm/dd",
                autoclose: true,
                todayHighlight: true,
            }).on('change', function(e){
                @this.set('visible_at', e.target.value);
            });

            $('#edit_ends_at').datepicker({
                dateFormat: "yy/mm/dd",
                autoclose: true,
                todayHighlight: true,
            }).on('change', function(e){
                @this.set('ends_at', e.target.value);
            });
        });

        window.addEventListener('showAddLink', event => {
            $('#visible_at').val('');
            $('#ends_at').val('');
            $('#addLink').modal('show');
        });

        window.addEventListener('hideAddLink', event => {
            $('#addLink').modal('hide');
        });

        window.addEventListener('showEditLink', event => {
            $('#editLink').modal('show');
        });

        window.addEventListener('hideEditLink', event => {
            $('#editLink').modal('hide');
        });

        window.addEventListener('confirmApprove', event => {
            $('#approveModal').modal('show');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('editDates', event => {
            $('#edit_visible_at').val(event.detail.start);
            $('#edit_ends_at').val(event.detail.end);
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
