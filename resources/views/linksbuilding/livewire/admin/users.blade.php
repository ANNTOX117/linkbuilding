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
                @if(permission('users', 'create'))
                    @if($tab == 'users')
                        <a data-toggle="modal" wire:click="modalAddUser" class="mr-2"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add user')}}</span></a>
                        <a data-toggle="modal" wire:click="modalAddCredits"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add credits')}}</span></a>
                    @endif
                    @if($tab == 'groups')
                        <a data-toggle="modal" wire:click="modalAddGroup"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add group')}}</span></a>
                    @endif
                    @if($tab == 'roles')
                        <a data-toggle="modal" wire:click="modalAddRole"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add role')}}</span></a>
                    @endif
                @endif
            </div>
        </div>

        <div class="cont ">
            <div class="card">
                <div class="card-body">
                    <div class="tab">
                        <a @if($tab == 'users') class="active" @endif wire:click="table('users')">{{__('Users') }}</a>
                        <a @if($tab == 'groups') class="active" @endif wire:click="table('groups')">{{__('Groups')}}</a>
                        <a @if($tab == 'roles') class="active" @endif wire:click="table('roles')">{{__('Roles')}}</a>
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

                        @if($tab == 'users')
                            <table class="table table-hover table-users">
                                <thead>
                                <tr>
                                    <th>{{__('Name')}} <a wire:click="sort('users', 'name')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Email')}} <a wire:click="sort('users', 'email')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Role')}} <a wire:click="sort('users', 'role')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Credits')}} <a wire:click="sort('users', 'credit')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Active')}} <a wire:click="sort('users', 'blocked')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Verified')}} <a wire:click="sort('users', 'email_verified_at')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Country')}} <a wire:click="sort('users', 'country')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Date')}} <a wire:click="sort('articles', 'created_at')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($users))
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->name }} {{ $user->lastname }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->roles ? $user->roles->description : '' }}</td>
                                            <td>{{ intval($user->credit) }}</td>
                                            <td>@if($user->block) <i class="fas fa-times-circle text-danger"></i> @else <i class="fas fa-check-circle text-success"></i> @endif</td>
                                            <td>@if(!empty($user->email_verified_at)) <i class="fas fa-check-circle text-success"></i> @else <i class="fas fa-times-circle text-danger"></i> @endif</td>
                                            <td>{{ $user->countries ? $user->countries->name : '' }}</td>
                                            <td>{{ date('Y/m/d', strtotime($user->created_at)) }}</td>
                                            <td>
                                                @if(permission('users', 'update'))
                                                    <a class="blues" wire:click="modalCredits({{$user->id}})" alt="{{__('Add credits')}}" title="{{__('Add credits')}}"><span class="block"><i class="fas fa-dollar-sign"></i></span></a>
                                                    <a class="blues" wire:click="modalEditUser({{$user->id}})" alt="{{__('Edit user')}}" title="{{__('Edit user')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('users', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$user->id}})" alt="{{__('Delete user')}}" title="{{__('Delete user')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have users added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($users))
                                {{ $users->links() }}
                            @endif
                        @endif

                        @if($tab == 'groups')
                            <table class="table table-hover table-groups">
                                <thead>
                                <tr>
                                    <th>{{__('Group')}} <a wire:click="sort('groups', 'name')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Members')}} <a wire:click="sort('groups', 'total')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($groups))
                                    @foreach($groups as $row)
                                        <tr>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->total }}</td>
                                            <td>
                                                @if(permission('users', 'update'))
                                                    <a class="blues" wire:click="modalEditGroup({{$row->id}})" alt="{{__('Edit group')}}" title="{{__('Edit group')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('users', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$row->id}})" alt="{{__('Delete group')}}" title="{{__('Delete group')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have groups added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($groups))
                                {{ $groups->links() }}
                            @endif
                        @endif

                        @if($tab == 'roles')
                            <table class="table table-hover table-roles">
                                <thead>
                                <tr>
                                    <th>{{__('Role name')}} <a wire:click="sort('roles', 'name')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Description')}} <a wire:click="sort('roles', 'description')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Permission')}}</th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($roles))
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>{{ $role->name }}</td>
                                            <td>{{ $role->description }}</td>
                                            <td>
                                                @if(permission('users', 'update'))
                                                    @if($role->name != 'customer')
                                                        <a wire:click="modalPermissions({{$role->id}})"><span class="add round btn-small reverse">{{__('Manage')}}</span></a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if(permission('users', 'update'))
                                                    <a class="blues" wire:click="modalEditRole({{$role->id}})" alt="{{__('Edit role')}}" title="{{__('Edit role')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('users', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$role->id}})" alt="{{__('Delete role')}}" title="{{__('Delete role')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have roles added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($roles))
                                {{ $roles->links() }}
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="addUser, editUser, addGroup, editGroup, addRole, editRole, addCredits, addAllCredits, table, sort, delete">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create user')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successUser'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successUser') }}
                                </div>
                            @endif
                            <div class="row form-group">
                                <div class="col">
                                    <label for="name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="name" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                    @error('name') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="lastname" class="form-label">{{__('Lastname')}}</label>
                                    <input wire:model="lastname" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                    @error('lastname') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company" class="form-label">{{__('Company')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="company" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('company') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">{{__('Email')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="email" type="email" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('email') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="role_user" class="form-label">{{__('Role')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="role_user" id="role_user" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($roles))
                                        @foreach($roles as $option)
                                            <option value="{{ $option->id }}">{{ $option->description }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('role_user') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="row form-group">
                                <div class="col">
                                    <label for="city" class="form-label">{{__('City')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="city" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                    @error('city') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="country" class="form-label">{{__('Country')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <select wire:model="country" id="country" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose an option')}}</option>
                                        @if(!empty($countries))
                                            @foreach($countries as $option)
                                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('country') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col">
                                    <label for="postal_code" class="form-label">{{__('Postal code')}}</label>
                                    <input wire:model="postal_code" type="text" class="form-control" maxlength="10" :errors="$errors" autocomplete="off" />
                                    @error('postal_code') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="kvk" class="form-label">{{__('KVK')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="kvk" type="text" class="form-control" maxlength="25" :errors="$errors" autocomplete="off" />
                                    @error('kvk') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="tax" class="form-label">{{__('Tax')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="tax" type="number" class="form-control" maxlength="4" :errors="$errors" autocomplete="off" />
                                    @error('tax') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addUser">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addUser">
                            <button type="button" wire:click="addUser" class="btn btn-primary">{{__('Save user')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit user')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successUser'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successUser') }}
                                </div>
                            @endif
                            <input type="hidden" wire:model="user_id" />
                            <div class="row form-group">
                                <div class="col">
                                    <label for="name" class="form-label">{{__('Name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="name" type="text" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                    @error('name') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="lastname" class="form-label">{{__('Lastname')}}</label>
                                    <input wire:model="lastname" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                    @error('lastname') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company" class="form-label">{{__('Company')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="company" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('company') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">{{__('Email')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="email" type="email" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('email') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="role_user_edit" class="form-label">{{__('Role')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="role_user" id="role_user_edit" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($roles))
                                        @foreach($roles as $option)
                                            <option value="{{ $option->id }}">{{ $option->description }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('role_user') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="row form-group">
                                <div class="col">
                                    <label for="city" class="form-label">{{__('City')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="city" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                    @error('city') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="country_edit" class="form-label">{{__('Country')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <select wire:model="country" id="country_edit" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose an option')}}</option>
                                        @if(!empty($countries))
                                            @foreach($countries as $option)
                                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('country') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col">
                                    <label for="postal_code" class="form-label">{{__('Postal code')}}</label>
                                    <input wire:model="postal_code" type="text" class="form-control" maxlength="10" :errors="$errors" autocomplete="off" />
                                    @error('postal_code') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="kvk" class="form-label">{{__('KVK')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="kvk" type="text" class="form-control" maxlength="25" :errors="$errors" autocomplete="off" />
                                    @error('kvk') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="tax" class="form-label">{{__('Tax')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input wire:model="tax" type="number" class="form-control" maxlength="4" :errors="$errors" autocomplete="off" />
                                    @error('tax') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editUser">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editUser">
                            <button type="button" wire:click="editUser" class="btn btn-primary">{{__('Update user')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create group')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successGroup'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successGroup') }}
                                </div>
                            @endif
                            @if(session()->has('errorGroup'))
                                <div class="alert alert-danger mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('errorGroup') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="group_name" class="form-label">{{__('Group name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="group_name" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('group_name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group" wire:ignore>
                                <label for="group_users" class="form-label">{{__('Users')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select class="form-control" multiple="multiple" id="select2-users" :errors="$errors" autocomplete="off">
                                    @if($members->isNotEmpty())
                                        @foreach($members as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} {{ $item->lastname }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('selected') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addGroup">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addGroup">
                            <button type="button" wire:click="addGroup" class="btn btn-primary">{{__('Save group')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit group')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successGroup'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successGroup') }}
                                </div>
                            @endif
                            @if(session()->has('errorGroup'))
                                <div class="alert alert-danger mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('errorGroup') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="group_name" class="form-label">{{__('Group name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="group_name" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('group_name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group" wire:ignore>
                                <label for="group_users" class="form-label">{{__('Users')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select class="form-control" multiple="multiple" id="select2-users-edit" :errors="$errors" autocomplete="off">
                                    @if($members->isNotEmpty())
                                        @foreach($members as $item)
                                            <option value="{{ $item->id }}" @if(in_array($item->id, $selected)) selected="selected" @endif>{{ $item->name }} {{ $item->lastname }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('selected') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editGroup">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editGroup">
                            <button type="button" wire:click="editGroup" class="btn btn-primary">{{__('Update group')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create role')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successRole'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successRole') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="role_name" class="form-label">{{__('Role name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="role_name" type="text" class="form-control" maxlength="25" :errors="$errors" autocomplete="off" />
                                @error('role_name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="role_description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="role_description" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('role_description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addRole">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addRole">
                            <button type="button" wire:click="addRole" class="btn btn-primary">{{__('Save role')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit role')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successRole'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successRole') }}
                                </div>
                            @endif
                            <input type="hidden" wire:model="role_id" />
                            <div class="form-group">
                                <label for="role_name" class="form-label">{{__('Role name')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="role_name" type="text" class="form-control" maxlength="25" :errors="$errors" autocomplete="off" />
                                @error('role_name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="role_description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="role_description" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('role_description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editRole">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editRole">
                            <button type="button" wire:click="editRole" class="btn btn-primary">{{__('Update role')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addcredits" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Add credits')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successAddCredits'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successAddCredits') }}
                                </div>
                            @endif
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label for="select2-customers" class="form-label">{{__('Clients')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <select wire:model="recipients" class="form-control" multiple="multiple" id="select2-customers" :errors="$errors" autocomplete="off">
                                    </select>
                                </div>
                                @error('recipients') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="credits" class="form-label">{{__('Credits')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="credits" type="number" class="form-control text-right" min="0" maxlength="6" :errors="$errors" autocomplete="off" />
                                @error('credits') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addAllCredits">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addAllCredits">
                            <button type="button" wire:click="addAllCredits" class="btn btn-primary">{{__('Add credits')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="credits" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Add credits')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successCredits'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successCredits') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="credits" class="form-label">{{__('Credits')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="credits" type="number" class="form-control text-right" min="0" maxlength="6" :errors="$errors" autocomplete="off" />
                                @error('credits') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addCredits">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addCredits">
                            <button type="button" wire:click="addCredits" class="btn btn-primary">{{__('Add credits')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="permissions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Permissions')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successPermissions'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successPermissions') }}
                                </div>
                            @endif
                            <table class="table table-hover table-permissions">
                                <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Create')}}</th>
                                        <th>{{__('Read')}}</th>
                                        <th>{{__('Update')}}</th>
                                        <th>{{__('Delete')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-capitalize">{{__('Dashboard')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_dashboard.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_dashboard.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_dashboard.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_dashboard.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Languages')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_languages.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_languages.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_languages.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_languages.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Categories')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_categories.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_categories.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_categories.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_categories.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Sites')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_sites.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_sites.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_sites.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_sites.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Wordpress')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_wordpress.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_wordpress.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_wordpress.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_wordpress.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Authority sites')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_authorities.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_authorities.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_authorities.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_authorities.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Articles')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_articles.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_articles.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_articles.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_articles.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Packages')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_packages.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_packages.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_packages.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_packages.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Links')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_links.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_links.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_links.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_links.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Approvals')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_approvals.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_approvals.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_approvals.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_approvals.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Users')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_users.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_users.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_users.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_users.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Mailing')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_mailing.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_mailing.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_mailing.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_mailing.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Taxes')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_taxes.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_taxes.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_taxes.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_taxes.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Static texts')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_texts.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_texts.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_texts.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_texts.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Pages')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_pages.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_pages.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_pages.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_pages.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Payments')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_payments.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_payments.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_payments.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_payments.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('Discounts')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_discounts.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_discounts.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_discounts.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_discounts.3" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-capitalize">{{__('General')}}</td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_general.0" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_general.1" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_general.2" /></td>
                                        <td><input class="form-check-input m-0 p-0" type="checkbox" wire:model="permissions_general.3" /></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editPermissions">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editPermissions">
                            <button type="button" wire:click="editPermissions" class="btn btn-primary">{{__('Edit permissions')}}</button>
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
                        <button type="button" wire:click.prevent="delete" class="btn btn-danger close-modal" data-dismiss="modal">
                            {{__('Yes, Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@section('javascript')
    <script>
        window.addEventListener('alert', event => {
            alert(event.detail.message);
        });

        window.addEventListener('modal', event => {
            if(event.detail) {
                $(event.detail).modal('show');
            }
        });
    </script>
@endsection

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            $('#select2-users').select2();
            $('#select2-users-edit').select2();
            $("#select2-customers").select2({ width: '100%' });

            $('#select2-users').on('change', function (e) {
                @this.set('selected', $('#select2-users').select2("val"));
            });

            $('#select2-users-edit').on('change', function (e) {
                @this.set('selected', $('#select2-users-edit').select2("val"));
            });

            $('#select2-customers').on('change', function (e) {
                let data = ($('#select2-customers').select2("val"));
                @this.set('recipients', data);
            });
        });

        window.addEventListener('resetRecipients', event => {
            $('#select2-customers').empty();
            $('#select2-customers').select2({data: event.detail.options, width: '100%'});

            // Prepend "groups"
            if(event.detail.groups !== undefined) {
                event.detail.groups.forEach( function(value, index) {
                    var newOption = new Option("{{__('Group')}}" + ': ' + value.name, "G:" + value.name, false, false);
                    $('#select2-customers').prepend(newOption).trigger('change');
                });
            }

            // Prepend "all customers"
            var newOption = new Option(event.detail.all, 0, false, false);
            $('#select2-customers').prepend(newOption).trigger('change');
        });

        window.addEventListener('resetCustomers', event => {
            $('#select2-users').empty();
            $('#select2-users').select2({data: event.detail.options, width: '100%'});
        });

        window.addEventListener('resetEditCustomers', event => {
            $('#select2-users-edit').empty();
            $('#select2-users-edit').select2({data: event.detail.options, width: '100%'});
        });

        window.addEventListener('showAddUser', event => {
            $('#addUser').modal('show');
        });

        window.addEventListener('hideAddUser', event => {
            $('#addUser').modal('hide');
        });

        window.addEventListener('showEditUser', event => {
            $('#editUser').modal('show');
        });

        window.addEventListener('hideEditUser', event => {
            $('#editUser').modal('hide');
        });

        window.addEventListener('showAddGroup', event => {
            $('select#select2-users').val(null).trigger('change');
            $('#addGroup').modal('show');
        });

        window.addEventListener('hideAddGroup', event => {
            $('#addGroup').modal('hide');
        });

        window.addEventListener('showEditGroup', event => {
            $('#editGroup').modal('show');
        });

        window.addEventListener('hideEditGroup', event => {
            $('#editGroup').modal('hide');
        });

        window.addEventListener('showAddRole', event => {
            $('#addRole').modal('show');
        });

        window.addEventListener('hideAddRole', event => {
            $('#addRole').modal('hide');
        });

        window.addEventListener('showEditRole', event => {
            $('#editRole').modal('show');
        });

        window.addEventListener('hideEditRole', event => {
            $('#editRole').modal('hide');
        });

        window.addEventListener('showCredits', event => {
            $('#credits').modal('show');
        });

        window.addEventListener('showAddCredits', event => {
            $('#addcredits').modal('show');
        });

        window.addEventListener('hideCredits', event => {
            $('#credits').modal('hide');
        });

        window.addEventListener('hideAddCredits', event => {
            $('#addcredits').modal('hide');
        });

        window.addEventListener('showPermissions', event => {
            $('#permissions').modal('show');
        });

        window.addEventListener('hidePermissions', event => {
            $('#permissions').modal('hide');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('showUsers', event => {
            if(event.detail.option == 'pictures') {
                $('#users_pictures_' + event.detail.index).show();
                $('#users_list_' + event.detail.index).hide();
            }
            if(event.detail.option == 'list') {
                $('#users_pictures_' + event.detail.index).hide();
                $('#users_list_' + event.detail.index).show();
            }
        });

        window.addEventListener('loadTooltip', event => {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
