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
		<div class="form-group col-md-6" wire:click="modalCreateSeoPage">
			<a data-toggle="modal" >
				<span class="add round btn-small reverse">
					<i class="fas fa-plus"></i>
					{{__('Add Seo page')}}
				</span>
			</a>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="table__show--message">
							@if ($message["insert"]["success"]["show"])
							<div class="alert alert-success">
								{{__($message["insert"]["success"]["msg"])}}
							</div>
							@endif
							@if ($message["insert"]["error"]["show"])
							<div class="alert alert-danger">
								{{__($message["insert"]["error"]["msg"])}}
							</div>
							@endif
						</div>
						<div class="table__seopages">
							<div class="text-center">
								<h1>{{__("Seo Pages")}}</h1>
							</div>
							<div class="form__filter">
								<div class="row">
									<div class="form-group col-md-3">
										<label for="country_choose" class="col-form-label">{{__("Country")}}</label>
										<select class="form-control" wire:model="country" id="country_choose">
											@if(!empty($countries))
												@foreach($countries as $id => $name)
													<option value={{$id}}>{{__($name)}}</option>
												@endforeach
											@endif
										</select>
									</div>
									<div class="form-group col-md-3">
										<label for="site_choose" class="col-form-label">{{__("Site")}}</label>
										<select class="form-control" wire:model="siteFilter" id="site_choose">
											<option value="">All Sites</option>
											@if(!empty($allSites))
												@foreach($allSites as $site)
													<option value={{$site->id}}>{{$site->name}}</option>
												@endforeach
											@endif
										</select>
									</div>
									<div class="form-group col-md-3">
										<label for="city_choose" class="col-form-label">{{__("City")}}</label>
										<select class="form-control" wire:model="cityFilter" id="city_choose">
											<option value="">All Cities</option>
											@if(!empty($citiesByCountry))
												@foreach($citiesByCountry as $city)
													<option value={{$city->id}}>{{$city->name}}</option>
												@endforeach
											@endif
										</select>
									</div>
									<div class="form-group col-md-3">
										<label for="category_choose" class="col-form-label">{{__("Category")}}</label>
										<select class="form-control" wire:model="category" id="category_choose">
											<option value="">All categories</option>
											@if(!empty($categories))
												@foreach($categories as $category)
													<option value={{$category->id}}>{{__($category->name)}}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
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
								<table class="table table-hover table-templates seopages__table">
									<thead>
										<tr>
											<th class="">{{__('Id')}}</th>
											<th class="">{{__('Category')}}</th>
											<th class="">{{__('site')}}</th>
											<th class="">{{__('City')}}</th>
											<th class="">{{__('Meta title')}}</th>
											<th class="">{{__('Meta description')}}</th>
											<th class="">{{__('Title')}}</th>
											<th class="">{{__('Description top')}}</th>
											<th class="">{{__('Description button')}}</th>
											<th class="">{{__('Text infront left')}}</th>
											<th class="">{{__('Text infront right')}}</th>
											<th class="">{{__('Status')}}</th>
											<th class="">{{__('Actions')}}</th>
										</tr>
									</thead>
									<tbody>
									@if(count($allSeoPages) > 0)
										@foreach($allSeoPages as $seoPage)
											<tr>
												<td class="tableTd__text--fixed">{{$seoPage->id}}</td>
												<td class="tableTd__text--fixed">{{__($seoPage->category)}}</td>
												<td class="tableTd__text--fixed"><a href="{{$seoPage->url}}">{{$seoPage->url}}</a></td>
												<td class="tableTd__text--fixed">{{$seoPage->city}}</td>
												<td class="tableTd__text--fixed">{{__(strip_tags($seoPage->meta_title))}}</td>
												<td class="tableTd__text--fixed">{{__(strip_tags($seoPage->meta_description))}}</td>
												<td class="tableTd__text--fixed">{{__(strip_tags($seoPage->title))}}</td>
												<td class="tableTd__text--fixed">{{__(strip_tags($seoPage->description_top))}}</td>
												<td class="tableTd__text--fixed">{{__(strip_tags($seoPage->description_buttom))}}</td>
												<td class="tableTd__text--fixed">{{__(strip_tags($seoPage->text_infront_left))}}</td>
												<td class="tableTd__text--fixed">{{__(strip_tags($seoPage->text_infront_right))}}</td>
												<td class="tableTd__text--fixed">{{__(($seoPage->active == 0)?"Inactive":"Active")}}</td>
												<td>
													@if(permission('seo-pages', 'update'))
														<a class="blues" wire:click="modalUpdateSeoPage({{$seoPage->id}})" alt="{{__('Edit profile')}}" title="{{__('Edit profile')}}"><span class="block"><i class="far fa-edit"></i></span></a>
													@endif
													@if(permission('seo-pages', 'delete'))
														<a class="reds" wire:click="modalDelete({{$seoPage->id}})" alt="{{__('Delete profile')}}" title="{{__('Delete profile')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
													@endif
												</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="13">
												<div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have seo pages added yet')}}</em></div>
											</td>
										</tr>
									@endif
									</tbody>
								</table>
								@if(count($allSeoPages) > 0)
									{{ $allSeoPages->links() }}
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div wire:ignore.self class="modal fade" id="addSeoPage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{__('Add SEO')}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="cleanInputsByModal">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal__form--file">
						<div class="border p-3">
							<p>The following shortcodes can be used:</p>
							<p>{stedenlink-{city}} , {provincie-{city}} , {city} ,{city} *,</p>
							<p>* only applicable when generating with cities.</p>
							<p>You can use the following notation to use synonyms:</p>
							<p>[synonym1|synonym2|synonym3|etc.]</p>
						</div>
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="category_add" class="col-form-label">Category</label>
								<select class="form-control" wire:model="category" id="category_add">
									<option value="">Select category</option>
									@if(!empty($categories))
										@foreach($categories as $category)
											<option value={{$category->id}}>{{$category->name}}</option>
										@endforeach
									@endif
								</select>
								@error('category') <span class="error">{{ $message }}</span> @enderror
							</div>
							<div class="form-group col-md-12" >
								<label for="site_add" class="col-form-label">Site</label>
								<select class="form-control" wire:model="siteFilter" id="site_add">
									<option value="">select Site</option>
									@if(!empty($allSites))
										@foreach($allSites as $site)
											<option value={{$site->id}}>{{$site->name}}</option>
										@endforeach
									@endif
								</select>
								@error('siteFilter') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="meta_title_add" class="col-form-label">Meta title</label>
								<input class="form-control" wire:model.lazy="meta_title" placeholder="Title of the metadata" id="meta_title_add">
								@error('meta_title') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="meta_description_add" class="form-label">{{__('Meta description')}}</label>
								<textarea class="form-control" id="meta_description" rows="3" wire:model="meta_description"></textarea>
								@error('meta_description') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="title_add" class="col-form-label">Title</label>
								<input class="form-control" wire:model.lazy="title_seo" placeholder="Title of the SEO" id="title_add">
								@error('title_seo') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
                                <label for="description_top" class="form-label">{{__('Description top')}} </label>
                                <div class="mb-3" wire:ignore>
                                    <div id="editor" x-data
                                         x-ref="quill"
                                         x-init="quill = new Quill($refs.quill, {theme: 'snow'});
                                            quill.on('text-change', function () {
                                                $dispatch('input', quill.root.innerHTML);
                                                @this.set('description_top', quill.root.innerHTML)
                                            });"
                                         wire:model.lazy="description_top">
                                        {!! $description_top !!}
                                    </div>
                                </div>
                                @error('description_top')<span class="error">{{ $message }}</span>@enderror
                            </div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
                                <label for="description_buttom" class="form-label">{{__('Description buttom')}} </label>
                                <div class="mb-3" wire:ignore>
                                    <div id="editor" x-data
                                         x-ref="quill_top"
                                         x-init="quill_top = new Quill($refs.quill_top, {theme: 'snow'});
                                            quill_top.on('text-change', function () {
                                                $dispatch('input', quill_top.root.innerHTML);
                                                @this.set('description_buttom', quill_top.root.innerHTML)
                                            });"
                                         wire:model.lazy="description_buttom">
                                        {!! $description_buttom !!}
                                    </div>
                                </div>
                                @error('description_buttom')<span class="error">{{ $message }}</span>@enderror
                            </div>
						</div>
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="text_infront_left_add" class="col-form-label">Text infront left</label>
								<input class="form-control" wire:model.lazy="text_infront_left" placeholder="Write text infront left" id="text_infront_left_add">
								@error('text_infront_left') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="text_infront_right_add" class="col-form-label">Text infront right</label>
								<input class="form-control" wire:model.lazy="text_infront_right" placeholder="Write text infront right" id="text_infront_right_add">
								@error('text_infront_right') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="text_city_choose" class="col-form-label">text city nearby</label>
								<input class="form-control" wire:model.lazy="text_city_nearby" placeholder="[synonym1|synonym2|synonym3|etc.]" id="text_city_choose">
								@error('text_city_nearby') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="active_seo" wire:model.lazy="activeSeoPage">
							<label class="form-check-label" for="active_seo">Active SEO</label>
						</div>
						<div wire:loading wire:target="addSeoPage">
							<img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
						</div>
						<div class="form__attributes--menu">
							<div class="text-center">
								<button wire:loading.remove wire:click="generateMetadata" class="btn btn-primary">{{__('Generate SEO example')}}</button>
								<button wire:loading.remove wire:click="addSeoPage" class="btn btn-primary">{{__('Insert Data')}}</button>
								<button wire:loading.remove wire:click="addSeoPageAsBgTask" class="btn btn-primary mt-3">{{__('Insert Data as backgroud task')}}</button>
							</div>
							<div class="text-center m-2">
								<button class="btn btn-secondary" wire:click="cleanInputsByModal" data-dismiss="modal">{{__('Cancel')}}</button>
							</div>
						</div>
						@if ($showMetadata)
							<div class="border p-4">
								<h5>Title:</h5>
								{!!$msgMetadata["title"]!!}
							</div>
							<div class="border p-4">
								<h5>Meta title:</h5>
								{!!$msgMetadata["meta_title"]!!}
							</div>
							<div class="border p-4">
								<h5>Meta description:</h5>
								{!!$msgMetadata["meta_description"]!!}
							</div>
							<div class="border px-2">
								@php
								echo "Description top: ".$msgMetadata["description_top"];
								@endphp
							</div>
							<div class="border px-2">
								@php
								echo "Description bottom: ".$msgMetadata["description_buttom"];
								@endphp
							</div>
							<h5>Text infront left:</h5>
							<h3>{{$text_infront_left}}</h3>
							<h5>Text infront right:</h5>
							<h3>{{$text_infront_right}}</h3>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<div wire:ignore.self class="modal fade" id="updateSeoPage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{__('Update SEO')}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="cleanModal">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal__form--file">
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="category_update" class="col-form-label">Category</label>
								<select class="form-control" wire:model="SeoPageToUpdateCategoryId" id="category_update">
									@if(!empty($categories))
										@foreach($categories as $category)
											<option value={{$category->id}}>{{$category->name}}</option>
										@endforeach
									@endif
								</select>
								@error('category') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="cities_update" class="col-form-label">{{__("City")}}</label>
								<select class="form-control" wire:model="SeoPageToUpdateCityId" id="cities_update">
									@if(!empty($citiesByCountry))
										@foreach($citiesByCountry as $city)
											<option value={{$city->id}}>{{$city->name}}</option>
										@endforeach
									@endif
								</select>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="meta_title_update" class="col-form-label">Meta title</label>
								<input class="form-control" wire:model.lazy="SeoPageToUpdateMetaTitle" placeholder="Title of the metadata" id="meta_title_update">
								@error('meta_title') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="meta_description_update" class="form-label">{{__('Meta description')}}</label>
								<textarea class="form-control" id="meta_description_update" rows="3" wire:model.lazy='SeoPageToUpdateMetaDescription'></textarea>
								@error('meta_description') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="title_update" class="col-form-label">Title</label>
								<input class="form-control" wire:model.lazy="SeoPageToUpdateTitle" placeholder="Title of the SEO" id="title_update">
								@error('title_seo') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="description_update" class="form-label">{{__('Description top')}} </label>
								<div wire:ignore>
									<div x-data
										 x-ref="quillEditor"
										 x-init="quillEditor = new Quill($refs.quillEditor, {theme: 'snow'});
										 	quillEditor.on('text-change', function () {
												$dispatch('input', quillEditor.root.innerHTML);
												@this.set('SeoPageToUpdateDescriptionTop', quillEditor.root.innerHTML)
											});"
										 wire:model.lazy="SeoPageToUpdateDescriptionTop">
										{!! $SeoPageToUpdateDescriptionTop !!}
									</div>
								</div>
								@error('SeoPageToUpdateDescriptionTop')<span class="error">{{ $message }}</span>@enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="description_update_buttom" class="form-label">{{__('Description buttom')}} </label>
								<div wire:ignore>
									<div x-data
										 x-ref="quillEditorButtom"
										 x-init="quillEditorButtom = new Quill($refs.quillEditorButtom, {theme: 'snow'});
										 	quillEditorButtom.on('text-change', function () {
												$dispatch('input', quillEditorButtom.root.innerHTML);
												@this.set('SeoPageToUpdateDescriptionButtom', quillEditorButtom.root.innerHTML)
											});"
										 wire:model.lazy="SeoPageToUpdateDescriptionButtom">
										{!! $SeoPageToUpdateDescriptionButtom !!}
									</div>
								</div>
								@error('SeoPageToUpdateDescriptionButtom')<span class="error">{{ $message }}</span>@enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="text_infront_left_update" class="col-form-label">{{__("Text infront left")}}</label>
								<input class="form-control" wire:model.lazy="SeoPageToUpdateTextInfrontLeft" placeholder="Text infront left in h3" id="text_infront_left_update">
								@error('SeoPageToUpdateTextInfrontLeft') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12" >
								<label for="text_infront_right_update" class="col-form-label">{{__("Text infront right")}}</label>
								<input class="form-control" wire:model.lazy="SeoPageToUpdateTextInfrontRight" placeholder="Text infront right in h3" id="text_infront_right_update">
								@error('SeoPageToUpdateTextInfrontRight') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="active_seo_update" wire:model.lazy="SeoPageToUpdateActive">
							<label class="form-check-label" for="active_seo_update">Active SEO</label>
						</div>
						<div wire:loading wire:target="addTemplate">
							<img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
						</div>
						<div class="form__attributes--menu">
							<div class="text-center">
								<button wire:loading.remove wire:click="updateSeoPage" class="btn btn-primary">{{__('Update Data')}}</button>
								<button class="btn btn-secondary" data-dismiss="modal" wire:click="cleanModal">{{__('Close')}}</button>
							</div>
						</div>
						<div class="modal__show--message">
							@if ($message["update"]["success"]["show"])
							<div class="alert alert-success">
								{{$message["update"]["success"]["msg"]}}
							</div>
							@endif
							@if ($message["update"]["error"]["show"])
							<div class="alert alert-danger">
								{{$message["update"]["error"]["msg"]}}
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div wire:ignore.self class="modal fade" id="deletePage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{__('Delete SEO')}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal__form--file">
						<div class="p-3 text-center">
							<p>{{__("Do you really want to delete the seo page?")}}</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
							<button type="button" wire:click="deleteSeoPage" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Delete')}}</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div wire:ignore.self class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{__('Error SEO')}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal__form--file">
						<div class="p-3 text-center">
							<p>{{__($textModalError)}}</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@push('scripts')
	<script>
		window.addEventListener('modalCreateSeoPage', event => {
			$('#addSeoPage').modal({backdrop: 'static', keyboard: false});
			let ql_editor = document.getElementsByClassName("ql-editor");
			ql_editor[0].innerHTML = "";
			ql_editor[1].innerHTML = "";
		});
		window.addEventListener('modalUpdateSeoPage', event => {
			quillEditor.pasteHTML(event.detail.description_top);
			quillEditorButtom.pasteHTML(event.detail.description_buttom);
			$('#updateSeoPage').modal({backdrop: 'static', keyboard: false});
		});
		window.addEventListener('modalDeleteSeoPage', event => {
			$('#deletePage').modal({backdrop: 'static', keyboard: false});
		});
		window.addEventListener('hideModal', event => {
			$('#deletePage').modal('hide');
		});
		window.addEventListener('hideModalInsertSeoPage', event => {
			$('#addSeoPage').modal('hide');
		});
		window.addEventListener('modalShowError', event => {
			@this.set('textModalError', event.detail.msg)
			$('#modalError').modal({backdrop: 'static', keyboard: false});
		});
	</script>
@endpush