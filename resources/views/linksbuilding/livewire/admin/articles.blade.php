@section('title')
    {{ $title }}
@endsection
@dump($_SERVER)
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
                @if(permission('articles', 'create'))
                    @if($tab == 'articles')
                        <a data-toggle="modal" wire:click="modalAddArticle"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add article')}}</span></a>
                    @endif
                    @if($tab == 'filters')
                        <a data-toggle="modal" wire:click="modalAddWord"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add bad word')}}</span></a>
                    @endif
                @endif
            </div>
        </div>

        <div class="cont ">
            <div class="card">
                <div class="card-body">
                    <div class="tab">
                        <a @if($tab == 'articles') class="active" @endif wire:click="table('articles')">{{__('Articles') }}</a>
                        <a @if($tab == 'requests') class="active" @endif wire:click="table('requests')">{{__('Requested articles') }}</a>
                        <a @if($tab == 'rules') class="active" @endif wire:click="table('rules')">{{__('Rules')}}</a>
                        <a @if($tab == 'filters') class="active" @endif wire:click="table('filters')">{{__('Bad word filter')}}</a>
                    </div>

                    @if ($tab != 'rules')
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
                    @endif

                    <div class="table-responsive">

                        @if($tab == 'articles')
                            <table class="table table-hover table-articles">
                                <thead>
                                <tr>
                                    <th>{{__('Title')}} <a wire:click="sort('articles', 'title')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Site')}} <a wire:click="sort('articles', 'authority_site')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Active')}} <a wire:click="sort('articles', 'active')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Date')}} <a wire:click="sort('articles', 'created_at')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($articles))
                                    @foreach($articles as $article)
                                        <tr>
                                            <td>{{ $article->title }}</td>
                                            <td>
                                                <a href="{{ $article->authority_sites ? $article->authority_sites->url : '#' }}" target="_blank" rel="nofollow">{{ @$article->authority_sites ? @$article->authority_sites->url : '' }}</a>
                                                @if(intval($article->error) == 1)
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="{{__('Error 404')}}">
                                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>@if($article->active) <i class="fas fa-check-circle text-success"></i> @else <i class="fas fa-times-circle text-danger"></i> @endif</td>
                                            <td>{{ date('Y/m/d', strtotime($article->created_at)) }}</td>
                                            <td>
                                                @if(permission('articles', 'update'))
                                                    <a class="blues" wire:click="modalEditArticle({{$article->id}})" alt="{{__('Edit article')}}" title="{{__('Edit article')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('articles', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$article->id}})" alt="{{__('Delete article')}}" title="{{__('Delete article')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have articles added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($articles))
                                {{ $articles->links() }}
                            @endif
                        @endif

                        @if($tab == 'requests')
                            <table class="table table-hover table-requests">
                                <thead>
                                <tr>
                                    <th>{{__('Customer')}} <a wire:click="sort('requests', 'customer')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Writer')}} <a wire:click="sort('requests', 'writer')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Title')}} <a wire:click="sort('requests', 'title')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Site')}} <a wire:click="sort('requests', 'authority_sites.url')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Ready')}}</th>
                                    <th>{{__('Request date')}} <a wire:click="sort('requests', 'created_at')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($requests))
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>{{ $request->customers ? $request->customers->name : '' }} {{ $request->customers ? $request->customers->lastname : '' }}</td>
                                            <td>@if(!empty($request->writer)) {{ $request->writers ? $request->writers->name : '' }} {{ $request->writers ? $request->writers->lastname : '' }} @else <span class="text-muted"><em>{{__('Unassigned')}}</em></span> @endif</td>
                                            <td>@if(!empty($request->title)) {{ $request->title }} @else <span class="text-muted"><em>{{__('Pending')}}</em></span> @endif</td>
                                            <td>@if(!empty($request->site)) {{ $request->site }} @else <span class="text-muted"><em>{{__('Unassigned')}}</em></span> @endif</td>
                                            <td>@if($request->ready) <i class="fas fa-check-circle text-success"></i> @else <i class="fas fa-times-circle text-danger"></i> @endif</td>
                                            <td>{{ date('Y/m/d', strtotime($request->created_at)) }}</td>
                                            <td>
                                                @if(permission('articles', 'update'))
                                                    @if(user_is_admin() or user_is_moderator())
                                                        <a class="blues" wire:click="confirmApprove({{$request->id}}, {{ ($request->ready) ? 'true' : 'false' }}, {{ ($request->automatic) ? 'true' : 'false' }})" alt="{{__('Approve article')}}" title="{{__('Approve article')}}"><span class="block"><i class="fas fa-check"></i></span></a>
                                                        <a class="blues" wire:click="assignWrite({{$request->id}})" alt="{{__('Writer')}}" title="{{__('Writer')}}"><span class="block"><i class="fas fa-user-plus"></i></span></a>
                                                    @endif
                                                    <a class="blues" wire:click="modalEditRequestedArticle({{$request->id ?? 0}}, {{ ($request->editable) ? 'true' : 'false' }})" alt="{{__('Edit article')}}" title="{{__('Edit article')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('articles', 'delete'))
                                                    <a class="reds" wire:click="confirmRequest({{$request->id}})" alt="{{__('Delete article')}}" title="{{__('Delete article')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have requested articles added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($requests))
                                {{ $requests->links() }}
                            @endif
                        @endif

                        @if($tab == 'rules')
                            <table class="table table-hover table-rules">
                                <thead>
                                <tr>
                                    <th>{{__('Min words')}}</th>
                                    <th>{{__('Max words')}}</th>
                                    <th>{{__('Max links')}}</th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($articles_rules))
                                    <tr>
                                        <td>
                                            @if($edit_rule)
                                                <input wire:model="min_words" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                @error('min_words') <span class="error">{{ $message }}</span> @enderror
                                            @else
                                                {{ $articles_rules->min_words ?? '0' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($edit_rule)
                                                <input wire:model="max_words" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                @error('max_words') <span class="error">{{ $message }}</span> @enderror
                                            @else
                                                {{ $articles_rules->max_words ?? '0' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($edit_rule)
                                                <input wire:model="max_links" type="number" class="form-control" :errors="$errors" autocomplete="off" />
                                                @error('max_links') <span class="error">{{ $message }}</span> @enderror
                                            @else
                                                {{ $articles_rules->max_links ?? '0' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(permission('articles', 'update'))
                                                @if($edit_rule)
                                                    <a class="blues" wire:click="saveRuleRow({{$articles_rules->id}})" alt="{{__('Save')}}" title="{{__('Save')}}"><span class="block"><i class="far fa-save"></i></span></a>
                                                    <a class="reds" wire:click="cancelRuleRow" alt="{{__('Cancel')}}" title="{{__('Cancel')}}"><span class="block"><i class="fas fa-times"></i></span></a>
                                                @else
                                                    <a class="blues" wire:click="editRuleRow({{$articles_rules->id}})" alt="{{__('Edit rule')}}" title="{{__('Edit rule')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="4">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have rules added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        @endif

                        @if($tab == 'filters')
                            <table class="table table-hover table-filters">
                                <thead>
                                <tr>
                                    <th>{{__('Word')}} <a wire:click="sort('filters', 'badword')"><i class="fas fa-sort"></i></a></th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($filters))
                                    @foreach($filters as $filter)
                                        <tr>
                                            <td>{{ $filter->badword }}</td>
                                            <td>
                                                @if(permission('articles', 'update'))
                                                    <a class="blues" wire:click="modalEditWord({{$filter->id}})" alt="{{__('Edit word')}}" title="{{__('Edit word')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                                @if(permission('articles', 'delete'))
                                                    <a class="reds" wire:click="confirm({{$filter->id}})" alt="{{__('Delete word')}}" title="{{__('Delete word')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have bad words added yet')}}</em></div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(!empty($filters))
                                {{ $filters->links() }}
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="addArticle, editArticle, addWord, editWord, table, sort, approve, assign, delete, deleteRequest">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="addArticle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create article')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successArticle'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successArticle') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="authority_site" class="form-label">{{__('Site')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="authority_site" id="authority_site" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($sites))
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}">{{ $site->url }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('authority_site') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="url" class="form-label">{{__('URL')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="url" type="url" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('url') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-label">{{__('Title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="name" type="text" class="form-control" maxlength="160" :errors="$errors" autocomplete="off" />
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                        x-ref="quillEditor"
                                        x-init="quill = new Quill($refs.quillEditor, {theme: 'snow'});
                                                quill.on('text-change', function () {
                                                    $dispatch('input', quill.root.innerHTML);
                                                    @this.set('description', quill.root.innerHTML)
                                                });"
                                        wire:model.lazy="description">
                                        {!! $description !!}
                                    </div>
                                </div>
                                @error('description')<span class="error">{{ $message }}</span>@enderror
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
                                <div>
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
                            </div>
                            <div id="content-images" class="form-group row">
                                <label class="col-12 col-form-label">{{__('Image')}}</label>
                                <div class="col-12">
                                    <div class="md-select image article {{ $list_ul }}">
                                        <label class="w-100 d-flex align-items-center" for="select_image_article">
                                            <button type="button" class="btn d-flex justify-content-between m-0 h-100">
                                                @if ($image_article != '')
                                                    <div class="section_select my-2">
                                                        <img class="img-fluid" src="{{ $image_article }}">
                                                    </div>
                                                @endif
                                            </button>
                                        </label>

                                        <ul role="listbox" id="select_image_article">
                                            <li id="search_imagen" class="d-flex align-items-center">
                                                <input wire:model="image_search" type="text" class="form-control" id="searchimagen">
                                                <button class="btn" wire:click="closeimage">
                                                    <i class="fas fa-window-close"></i>
                                                </button>
                                            </li>
                                            <li id="list_preview">
                                                <ul role="listbox" id="image_preview">
                                                    @if (!empty($image_array))
                                                        @foreach ($image_array as $image)
                                                            <li class="suggestions" wire:click="clicked_image({{ $image->id }})">
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <img src="{{ $image->src->tiny }}" alt="">
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <span class="mt-2">{{ __("Author:") }}
                                                                            <a href="{{ $image->photographer_url }}" class="px-ph" target="_blank">{{ $image->photographer }}</a>
                                                                            <br>{{ __("Dimensions:") }}{{ $image->width}}x{{ $image->height }}{{ __("pixels:") }}
                                                                            <br>{{ __("ID: #") }}{{ $image->photographer_id}}
                                                                            <br>{{ __("Source:")}}<a href="https://www.pexels.com" class="px-ph" target="_blank">pexels.com</a>
                                                                        </span>
                                                                    </div>
                                                                    <div class="section_select">
                                                                        <p class="m-0 row align-items-center">
                                                                            <span class="col-1 px-0">
                                                                                <img class="img-fluid" src="{{ $image->src->small}}"></span>
                                                                            <span class="col-auto">{{ __("Author:") }}{{ $image->photographer}}</span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @error('image_article') <span class="error">{{ $message }}</span> @enderror
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
                        <div wire:loading wire:target="addArticle">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addArticle">
                            <button type="button" wire:click="addArticle" class="btn btn-primary">{{__('Save article')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editArticle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mw-800" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit article')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successArticle'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successArticle') }}
                                </div>
                            @endif

                            @if($suggestions)
                                <div class="form-group">
                                    <div class="alert alert-success mb-3 mt-4">
                                        <strong>Suggestions:</strong>
                                        @if(count($suggestion_url) > 0)
                                            @for($i = 0; $i < count($suggestion_url); $i++)
                                                <p class="mb-0">{{__('Link')}} {{($i + 1)}}: {{ $suggestion_url[$i] }}</p>
                                                <p class="mb-0">{{__('Anchor')}} {{($i + 1)}}: {{ $suggestion_anchor[$i] }}</p>
                                            @endfor
                                        @endif
                                    </div>
                                </div>
                            @endif

                             <div class="form-group">
                                <label for="authority_site_edit" class="form-label">{{__('Site')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <select wire:model="authority_site" id="authority_site_edit" class="form-control" :errors="$errors">
                                    <option value="">{{__('Choose an option')}}</option>
                                    @if(!empty($sites))
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}">{{ $site->url }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('authority_site') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="url" class="form-label">{{__('URL')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="url" type="url" class="form-control" maxlength="255" :errors="$errors" autocomplete="off" />
                                @error('url') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-label">{{__('Title')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="name" type="text" class="form-control" maxlength="160" :errors="$errors" autocomplete="off" />
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{__('Description')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <div class="mb-3" wire:ignore>
                                    <div x-data
                                         x-ref="quillEdit"
                                         x-init="
                                         quill_data = '{{ $description }}';
                                         quill_edit = new Quill($refs.quillEdit, {theme: 'snow'});
                                         quill_edit.on('text-change', function () {
                                           set_description(quill_edit.root.innerHTML);
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
                                <div>
                                    <label for="language" class="form-label">{{__('Language')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <select wire:model.lazy="language" id="edit_language" class="form-control" :errors="$errors">
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
                            <div class="form-group">
                                <div>
                                    <label for="category" class="form-label">{{__('Category')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <select wire:model.lazy="category" id="edit_category" class="form-control" :errors="$errors">
                                        <option value="">{{__('Choose an option')}}</option>
                                        @if(!empty($categories))
                                            @foreach($categories as $option)
                                                <option value="{{ $option->id }}">{{ $option->text }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('category') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div id="content-images" class="form-group row">
                                <label class="col-12 col-form-label">{{__('Image')}}</label>
                                <div class="col-12">
                                    <div class="md-select image article {{ $list_ul }}">
                                        <label class="w-100 d-flex align-items-center" for="select_image_article">
                                            <button type="button" class="btn d-flex justify-content-between m-0 h-100">
                                                @if ($image_article != '')
                                                <div class="section_select my-2">
                                                    <img class="img-fluid" src="{{ $image_article }}">
                                                </div>
                                                @endif
                                            </button>
                                        </label>

                                        <ul role="listbox" id="select_image_article">
                                            <li id="search_imagen" class="d-flex align-items-center">
                                                <input wire:model="image_search" type="text" class="form-control" id="searchimagen">
                                                <button class="btn" wire:click="closeimage">
                                                    <i class="fas fa-window-close"></i>
                                                </button>
                                            </li>
                                            <li id="list_preview">
                                                <ul role="listbox" id="image_preview">
                                                    @if (!empty($image_array))
                                                        @foreach ($image_array as $image)
                                                            <li class="suggestions" wire:click="clicked_image({{ $image->id }})">
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <img src="{{ $image->src->tiny }}" alt="">
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <span class="mt-2">{{ __("Author:") }}
                                                                            <a href="{{ $image->photographer_url }}" class="px-ph" target="_blank">{{ $image->photographer }}</a>
                                                                            <br>{{ __("Dimensions:") }}{{ $image->width}}x{{ $image->height }}{{ __("pixels:") }}
                                                                            <br>{{ __("ID: #") }}{{ $image->photographer_id}}
                                                                            <br>{{ __("Source:")}}<a href="https://www.pexels.com" class="px-ph" target="_blank">pexels.com</a>
                                                                        </span>
                                                                    </div>
                                                                    <div class="section_select">
                                                                        <p class="m-0 row align-items-center">
                                                                            <span class="col-1 px-0">
                                                                                <img class="img-fluid" src="{{ $image->src->small}}"></span>
                                                                            <span class="col-auto">{{ __("Author:") }}{{ $image->photographer}}</span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @error('image_article') <span class="error">{{ $message }}</span> @enderror
                            </div>


                            <div class="row">
                                <div class="col">
                                    <label for="edit_visible_at" class="form-label">{{__('Start date')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input id="edit_visible_at" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                    @error('visible_at') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="col">
                                    <label for="edit_ends_at" class="form-label">{{__('End date')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                    <input id="edit_ends_at" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                    @error('ends_at') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @if(!$requested)
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
                        <div wire:loading wire:target="editArticle">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editArticle">
                            <button type="button" wire:click="editArticle" class="btn btn-primary">{{__('Update article')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addWord" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Create bad word')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successWord'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successWord') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="word" class="form-label">{{__('Word')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="word" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('word') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="addWord">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="addWord">
                            <button type="button" wire:click="addWord" class="btn btn-primary">{{__('Save word')}}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editWord" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Edit bad word')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="inside-form">
                            @if(session()->has('successWord'))
                                <div class="alert alert-success mb-3 mt-4 alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ session('successWord') }}
                                </div>
                            @endif
                            <input type="hidden" wire:model="word_id" />
                            <div class="form-group">
                                <label for="word" class="form-label">{{__('Word')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="word" type="text" class="form-control" maxlength="50" :errors="$errors" autocomplete="off" />
                                @error('word') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="inside-form mt-1 pb-0">
                            <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div wire:loading wire:target="editWord">
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                        <div wire:loading.remove wire:target="editWord">
                            <button type="button" wire:click="editWord" class="btn btn-primary">{{__('Update word')}}</button>
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
                        <p>{{__('Are you sure want to approve this requested article?')}}</p>
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
                        <p>{{__('Are you sure want to delete this :item?', ['item' => $item])}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="delete" class="btn btn-danger close-modal" data-dismiss="modal">
                            {{__('Yes, Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="confirmRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Confirm delete')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p>{{__('Are you sure want to delete this requested article?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="deleteRequest" class="btn btn-danger close-modal" data-dismiss="modal">
                            {{__('Yes, Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Assign writer')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" wire:ignore>
                            <label for="assigned" class="form-label">{{__('Writer')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                            <select wire:model="assigned" class="form-control" id="select2-edit-writers" :errors="$errors" autocomplete="off">
                                <option value="">{{__('Choose an option')}}</option>
                                @if(!empty($writers))
                                    @foreach($writers as $item)
                                        <option value="{{ $item->id }}">{{ $item->description }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('assigned') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="assign" class="btn btn-primary close-modal" data-dismiss="modal">
                            {{__('Assign writer')}}</button>
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

        $('.md-select.image.article label').on('click', function(){
            window.livewire.emit('clicked_image' , null);
        });

        document.addEventListener('livewire:load', function () {
            $('#select2-edit-writers').select2();

            $('#select2-edit-writers').on('change', function (e) {
                let data = ($('#select2-edit-writers').select2("val"));
                @this.set('assigned', data);
            });

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

        window.addEventListener('resetWriters', event => {
            $('#select2-edit-writers').empty();
            $('#select2-edit-writers').select2({data: event.detail.options});
        });

        window.addEventListener('showAddArticle', event => {
            $('#visible_at').val('');
            $('#ends_at').val('');
            $('#addArticle').modal('show');
        });

        window.addEventListener('hideAddArticle', event => {
            $('#addArticle').modal('hide');
        });

        window.addEventListener('showEditArticle', event => {
            quill_edit.container.firstChild.innerHTML = event.detail.editor;
            $('#editArticle').modal('show');
        });

        window.addEventListener('hideEditArticle', event => {
            $('#editArticle').modal('hide');
        });

        window.addEventListener('showAddWord', event => {
            $('#addWord').modal('show');
        });

        window.addEventListener('hideAddWord', event => {
            $('#addWord').modal('hide');
        });

        window.addEventListener('showEditWord', event => {
            $('#editWord').modal('show');
        });

        window.addEventListener('hideEditWord', event => {
            $('#editWord').modal('hide');
        });

        window.addEventListener('confirmApprove', event => {
            $('#approveModal').modal('show');
        });

        window.addEventListener('confirmAssign', event => {
            $('#assignModal').modal('show');
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('confirmDeleteRequest', event => {
            $('#confirmRequestModal').modal('show');
        });

        window.addEventListener('showError', event => {
            $('#errorModal').modal('show');
        });

        window.addEventListener('editDates', event => {
            $('#edit_visible_at').val(event.detail.start);
            $('#edit_ends_at').val(event.detail.end);
        });

        function set_description(text) {
            @this.set('description', text);
        }

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
