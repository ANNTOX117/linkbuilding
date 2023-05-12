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
                @if(permission('templates', 'create'))
                    <a data-toggle="modal" wire:click="modalAddTemplate">
                    <span class="add round btn-small reverse">
                        <i class="fas fa-plus"></i>
                        {{__('Add template')}}
                    </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if($message["insert"]["success"]["show"])
                            <div class="alert alert-success">
                                {{$message["insert"]["success"]["msg"]}}
                            </div>
                        @endif
                        @if($message["delete"]["success"]["show"])
                            <div class="alert alert-success">
                                {{$message["delete"]["success"]["msg"]}}
                            </div>
                        @endif
                        @if($message["update"]["success"]["show"])
                            <div class="alert alert-success">
                                {{$message["update"]["success"]["msg"]}}
                            </div>
                        @endif
                        @if(!empty($allTemplates))
                            <div class="table__templates">
                                <div class="text-center">
                                    <h1>templates</h1>
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
                                    <table class="table table-hover table-templates">
                                        <thead>
                                        <tr>
                                            <th class="w-5">{{__('Id')}}</th>
                                            <th class="w-10">{{__('Name')}}</th>
                                            <th class="w-75">{{__('Sites')}}</th>
                                            <th class="w-10">{{__('Accion')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($allTemplates[0]) )
                                            @foreach($allTemplates as $template)
                                                <tr>
                                                    <td>{{ $template->id }}</td>
                                                    <td>{{ $template->name }}</td>
                                                    <td>{{ $template->sites }}</td>
                                                    <td>
                                                        @if(permission('templates_by_site', 'update'))
                                                            <a class="blues" wire:click="modalEditTemplate({{$template->id}})" alt="{{__('Edit profile')}}" title="{{__('Edit profile')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                            <a class="blues" wire:click="modalMetaDataTemplate({{$template->id}})" alt="{{__('Add metadata')}}" title="{{__('Add metadata')}}"><span class="block"><i class="fas fa-cog"></i></span></a>
                                                        @endif
                                                        @if(!isset($template->sites))
                                                            @if(permission('templates_by_site', 'delete'))
                                                                <a class="reds" wire:click="confirm({{$template->id}})" alt="{{__('Delete profile')}}" title="{{__('Delete profile')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4">
                                                    <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have templates added yet')}}</em></div>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    @if(!empty($allTemplates[0]))
                                        {{ $allTemplates->links() }}
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="addTemplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Insert template')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="cleanInputs">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal__form--template">
                            <div class="form-group">
                                <label for="name_template" class="form-label">{{__('Name of the template')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                <input wire:model="nameTemplate" type="text" class="form-control" id="name_template" placeholder="Write the name of the template"/>
                                @error('nameTemplate') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group"  wire:ignore>
                                <div class="w-100 d-flex justify-content-between">
                                    <label for="sites" class="form-label">{{__('Sites from the template')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                                </div>
                                <select wire:model="sites" class="form-control" id="select2-choose-sites" multiple="multiple">
                                    @if(!empty($allSites))
                                        @foreach($allSites as $site)
                                            <option value="{{ $site->id }}">{{ $site->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @error('sites') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="cleanInputs" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Cancel')}}</button>
                        <button type="button" wire:click="insertTemplate" class="btn btn-info">{{__('Add template')}}</button>
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
                        <p>{{__('Are you sure want to delete this template?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="delete" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Update template')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"  wire:ignore>
                            <div class="w-100 d-flex justify-content-between">
                                <label for="sites" class="form-label">{{__('Sites from the template')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                            </div>
                            <select wire:model="sitesByTemplate" class="form-control" id="select2-update-sites" multiple="multiple">
                                @if(!empty($allSites))
                                    @foreach($allSites as $site)
                                        <option value="{{ $site->id }}">{{ $site->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @error('sites') <span class="error">{{ $message }}</span> @enderror
                        <div wire:loading>
                            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="updateTemplate" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Update')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="addMetaDataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Add metadata')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <div class="row">
                            <div class="form-group col-md-12">
                                <label for="">{{__('Text title')}}</label>
                                <input type="text" class="form-control" wire:model.lazy="titleText" placeholder="text of the page"/>
                                @error('titleText')<span class="error">{{ $message }}</span>@enderror
                            </div>
                        </div> --}}
                        <div class="row">
							<div class="form-group col-md-12">
								<label for="contentTopRegister" class="form-label">{{__('Description sing up top')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
								<div wire:ignore>
									<div x-data
										 x-ref="quillTopRegisterTop"
										 x-init="quillTopRegisterTop = new Quill($refs.quillTopRegisterTop, {theme: 'snow'});
										 	quillTopRegisterTop.on('text-change', function () {
												$dispatch('input', quillTopRegisterTop.root.innerHTML);
												@this.set('contentTopRegister', quillTopRegisterTop.root.innerHTML)
											});"
										 wire:model.lazy="contentTopRegister">
										{!! $contentTopRegister !!}
									</div>
								</div>
								@error('contentTopRegister')<span class="error">{{ $message }}</span>@enderror
							</div>
						</div>
                        <div class="form-group">
                            <label for="imageTop">{{__('Image sign up top')}}</label>
                            <input type="file" class="form-control-file file_images @error('imageTop') is_error @enderror" id="imageTop" wire:model.lazy="imageTop" accept="image/png, image/gif, image/jpeg" />
                            @if($imageTop)
                                @php
                                    try {
                                        echo '<img src="'.$imageTop->temporaryUrl().'" class="img-thumbnail w-100 mt-3" />';
                                    } catch (\Throwable $th) {
                                        echo '<img src="'.$imageTop.'" class="img-thumbnail w-100 mt-3" />';
                                    }        
                                @endphp
                            @endif
                            @error('imageTop')<span class="error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="imageButtom">{{__('Image sign up buttom')}}</label>
                            <input type="file" class="form-control-file @error('imageButtom') is_error @enderror" id="imageButtom" wire:model.lazy="imageButtom" accept="image/png, image/gif, image/jpeg"/>
                            @if($imageButtom)
                                @php
                                    try {
                                        echo '<img src="'.$imageButtom->temporaryUrl().'" class="img-thumbnail w-100 mt-3" />';
                                    } catch (\Throwable $th) {
                                        echo '<img src="'.$imageButtom.'" class="img-thumbnail w-100 mt-3" />';
                                    }        
                                @endphp
                            @endif
                            @error('imageButtom')<span class="error">{{ $message }}</span>@enderror
                        </div>
                        <div class="row">
							<div class="form-group col-md-12">
								<label for="contentButtomRegister" class="form-label">{{__('Description sing up buttom')}} </label>
								<div wire:ignore>
									<div x-data
										 x-ref="quillTopRegisterButtom"
										 x-init="quillTopRegisterButtom = new Quill($refs.quillTopRegisterButtom, {theme: 'snow'});
										 	quillTopRegisterButtom.on('text-change', function () {
												$dispatch('input', quillTopRegisterButtom.root.innerHTML);
												@this.set('contentButtomRegister', quillTopRegisterButtom.root.innerHTML)
											});"
										 wire:model.lazy="contentButtomRegister">
										{!! $contentButtomRegister !!}
									</div>
								</div>
								@error('contentButtomRegister')<span class="error">{{ $message }}</span>@enderror
							</div>
						</div>
                        <div class="row">
							<div class="form-group col-md-12">
								<label for="contentFooterLeft" class="form-label">{{__('Footer left data')}} </label>
								<div wire:ignore>
									<div x-data
										 x-ref="quillFooterLeft"
										 x-init="quillFooterLeft = new Quill($refs.quillFooterLeft, {theme: 'snow'});
										 	quillFooterLeft.on('text-change', function () {
												$dispatch('input', quillFooterLeft.root.innerHTML);
												@this.set('contentFooterLeft', quillFooterLeft.root.innerHTML)
											});"
										 wire:model.lazy="contentFooterLeft">
										{!! $contentFooterLeft !!}
									</div>
								</div>
								@error('contentFooterLeft')<span class="error">{{ $message }}</span>@enderror
							</div>
						</div>
                        <div class="row">
							<div class="form-group col-md-12">
								<label for="contentFooterCenter" class="form-label">{{__('Footer center data')}} </label>
								<div wire:ignore>
									<div x-data
										 x-ref="quillFooterCenter"
										 x-init="quillFooterCenter = new Quill($refs.quillFooterCenter, {theme: 'snow'});
										 	quillFooterCenter.on('text-change', function () {
												$dispatch('input', quillFooterCenter.root.innerHTML);
												@this.set('contentFooterCenter', quillFooterCenter.root.innerHTML)
											});"
										 wire:model.lazy="contentFooterCenter">
										{!! $contentFooterCenter !!}
									</div>
								</div>
								@error('contentFooterCenter')<span class="error">{{ $message }}</span>@enderror
							</div>
						</div>
                        <div class="row">
							<div class="form-group col-md-12">
								<label for="contentFooterRight" class="form-label">{{__('Footer right data')}} </label>
								<div wire:ignore>
									<div x-data
										 x-ref="quillFooterRight"
										 x-init="quillFooterRight = new Quill($refs.quillFooterRight, {theme: 'snow'});
										 	quillFooterRight.on('text-change', function () {
												$dispatch('input', quillFooterRight.root.innerHTML);
												@this.set('contentFooterRight', quillFooterRight.root.innerHTML)
											});"
										 wire:model.lazy="contentFooterRight">
										{!! $contentFooterRight !!}
									</div>
								</div>
								@error('contentFooterRight')<span class="error">{{ $message }}</span>@enderror
							</div>
						</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="addExtraSettings" class="btn btn-primary">{{__('Set up settings')}}</button>
                    </div>
                    @if ($message["add_settings"]["success"]["show"])
                    <div class="alert alert-success m-3">
                        {{$message["add_settings"]["success"]["msg"]}}
                    </div>
                    @endif
                    @if ($message["add_settings"]["error"]["show"])
                    <div class="alert alert-danger m-3">
                        {{$message["add_settings"]["error"]["msg"]}}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <livewire:templates.modals.modal-error/>
    </div>
</div>    
@push("scripts")
<script>
    window.addEventListener('confirmDelete', event => {
        $('#confirmModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    window.addEventListener('dismissModal', event => {
        $('#addTemplate').modal('hide');
    });
    window.addEventListener('errorModal', event => {
        $('#errorModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    window.addEventListener('updateModalTemplate', event => {
        $('#select2-update-sites').trigger('change');
        $('#updateModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    window.addEventListener('showAddTemplate', event => {
        $('#addTemplate').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    document.addEventListener('livewire:load', function () {
        $('#select2-choose-sites').select2();
        $('#select2-choose-sites').on('change', function (e) {
            let data = ($('#select2-choose-sites').select2("val"));
            @this.set('sites', data);
        });
        $('#select2-update-sites').select2();
        $('#select2-update-sites').on('change', function (e) {
            let data = ($('#select2-update-sites').select2("val"));console.log(data);
            @this.set('sitesByTemplate', data);
        });
        window.addEventListener('resetTemplates', event => {
            $('#select2-update-sites').empty();
            $('#select2-update-sites').select2({data: event.detail.options, width: '100%'});
        });
    });
    window.addEventListener('addMetadata', event => {
        const fileInputs = Array.from(document.getElementById("addMetaDataModal").getElementsByTagName('input')).filter(input => input.type === 'file');
        fileInputs.map(input=>input.value="");
        if (JSON.stringify(event.detail) != '{}') {
            quillTopRegisterTop.pasteHTML(event.detail.contentTopRegister);
            quillTopRegisterButtom.pasteHTML(event.detail.contentButtomRegister);
            quillFooterLeft.pasteHTML(event.detail.contentFooterLeft);
            quillFooterCenter.pasteHTML(event.detail.contentFooterCenter);
            quillFooterRight.pasteHTML(event.detail.contentFooterRight);
        }else{
            @this.set('imageTop', "");
            @this.set('imageButtom', "");
            let ql_editor = document.getElementsByClassName("ql-editor");
            ql_editor = Array.from(ql_editor);
            ql_editor.map(ql=>ql.innerHTML = "");
        }
        $('#addMetaDataModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
</script>
@endpush