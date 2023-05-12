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
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="form-group col-md-6">
								<label for="page_choose" class="col-form-label">Select starting page</label>
								<select class="form-control" id="page_choose" wire:model="idSite" wire:change="getCategoriesBySite">
									@if(!empty($allSites))
										<option value="">Select Site</option>
										@foreach($allSites as $site)
											<option value={{htmlspecialchars($site->id)}}>{{htmlspecialchars($site->name)}}</option>
										@endforeach
									@endif
								</select>
								@error('idSite') <span class="error">{{ $message }}</span> @enderror
							</div>
							<div class="form-group col-md-6" >
								<label for="category_choose" class="col-form-label">Category</label>
								<select class="form-control" wire:model="category" id="category_choose">
									<option value="">Select category</option>
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
							<div class="form-group col-md-6">
								<label for="country_choose" class="col-form-label">{{__('Select the country')}}</label>
								<select class="form-control" id="country_choose" wire:model="idCountry">
									@if(!empty($allCountries))
										<option value="">{{__('Choose the country')}}</option>
										@foreach($allCountries as $country)
											<option value={{htmlspecialchars($country["id"])}}>{{htmlspecialchars($country["name"])}}</option>
										@endforeach
									@endif
								</select>
								@error('idCountry') <span class="error">{{ $message }}</span> @enderror
							</div>
							<div class="form-group col-md-6" >
								<a data-toggle="modal" wire:click="modalAddProfiles">
									<span class="add round btn-small reverse">
										<i class="fas fa-plus"></i>
										{{__('Add Profiles')}}
									</span>
								</a>
								<a data-toggle="modal" wire:click="confirmDeleteOldProfiles">
									<span class="add round btn-small reverse">
										<i class="fas fa-plus"></i>
										{{__('Remove old Profiles')}}
									</span>
								</a>
							</div>
						</div>
                        <div class="row">
							<div class="col-md-1">
								<input type="checkbox" value="" id="imporImageCheckbox" wire:model="importImage">
								<label class="form-check-label" for="imporImageCheckbox">
									Import Image
								</label>
							</div>
							@if(!$importImage)
							<div class="form-group col-md-11">
								<label for="pathImage">Custom path</label>
								<input type="text" class="form-control" id="pathImage" placeholder="/storage/profile_images/{id_site}" wire:model="pathImage">
								@error('pathImage') <span class="error">{{ $message }}</span> @enderror
							</div>
							@endif
                        </div>
						@if($idSite != "")
						<hr>
						<div class="table__profiles">
							<div class="text-center">
								<h1>Profiles</h1>
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
								<table class="table table-hover table-templates profiles__table">
									<thead>
										<tr>
											<th class="w-5">{{__('Id')}}</th>
											<th class="w-5">{{__('Profile id')}}</th>
											<th class="w-5">{{__('Images')}}</th>
											<th class="w-30">{{__('Title')}}</th>
											<th class="w-5">{{__('City')}}</th>
											<th class="w-50">{{__('Description')}}</th>
											<th class="w-5">{{__('Accion')}}</th>
										</tr>
									</thead>
									<tbody>
									
									@if(!empty($allSitesRender[0]))		
										@foreach($allSitesRender as $article)
											<tr>
												<td>{{ $article->id }}</td>
												<td>{{$article->profileId}}</td>
												<td><a href='{{$article->image}}' target="_blank"><img src='{{$article->image}}' alt="No image found"/></a></td>
												<td>{{ $article->title }}</td>
												<td>{{ $article->value }}</td>
												<td class="text-justify">{{ strip_tags($article->description)}}</td>
												<td>
													@if(permission('articles_by_site', 'update'))
														<a class="blues" wire:click="modalEditArticle({{$article->id}})" alt="{{__('Edit profile')}}" title="{{__('Edit profile')}}"><span class="block"><i class="far fa-edit"></i></span></a>
													@endif
													<a class="blues" wire:click="modalWriteReview({{$article->id}})" alt="{{__('Write review')}}" title="{{__('Write review')}}"><span class="block"><i class="fas fa-comments"></i></span></a>
													@if(permission('articles_by_site', 'delete'))
														<a class="reds" wire:click="confirm({{$article->id}})" alt="{{__('Delete profile')}}" title="{{__('Delete profile')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
													@endif
												</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="6">
												<div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have articles added yet')}}</em></div>
											</td>
										</tr>
									@endif
									</tbody>
								</table>
								@if(!empty($allSitesRender[0]))
									{{ $allSitesRender->links() }}
								@endif
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<div wire:ignore.self class="modal fade" id="addProfiles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{__('Import profiles')}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanInputFile()" wire:click="cleanInputsByModal">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal__form--file">
						@if($messageFileImport["success"]["show"])
							<div class="alert alert-success">
								{{$messageFileImport["success"]["msg"]}}
							</div>
						@endif
						@if($messageFileImport["success_bg"]["show"])
							<div class="alert alert-success">
								{{$messageFileImport["success"]["msg"]}}
							</div>
						@endif
						@if($messageFileImport["error"]["show"])
							<div class="alert alert-danger">
								{{$messageFileImport["error"]["msg"]}}
							</div>
						@endif
						@if($messageFileImport["error_dir"]["show"])
							<div class="alert alert-danger">
								{{$messageFileImport["error_dir"]["msg"]}}
							</div>
						@endif
						<div class="form-group">
							<label for="profiles_csv" class="form-label">{{__('Select the CSV')}}</label>
							<input wire:model="fileCsvProfiles" type="file" class="form-control-file" id="profiles_csv" accept="text/csv"/>
							@error('fileCsvProfiles') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div wire:loading>
							<img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
						</div>
						@isset($headers)
							<div class="form__attributes--menu">
								@foreach($profileAttributes as $attibute=>$value)
									<div class="form-group col-12 row">
										<div class="col-md-4">
											<label for="{{$attibute}}_choose" class="form-label">{{__(ucfirst(str_replace("_"," ",$attibute)))}}</label>
										</div>
										<div class="col-md-8">
											<select class="form-control" id="{{$attibute}}_choose" wire:model="profileAttributes.{{$attibute}}">
												<option value="">{{__('Choose the header of the file')}}</option>
												@foreach(json_decode($headers,true) as $value=>$header)
													<option value={{$value}} >{{$header}}</option>
												@endforeach
											</select>
											@error("profileAttributes.$attibute") <span class="error">{{ $message }}</span> @enderror
										</div>
									</div>
								@endforeach
								<div class="text-center">
									<button wire:loading.remove wire:click="uploadFormFile"class="btn btn-primary">{{__('Import Data')}}</button>
									<button wire:loading.remove wire:click="uploadFormFileBgTask"class="btn btn-primary">{{__('Import Data in background')}}</button>
									<button class="btn btn-secondary" data-dismiss="modal" onclick="cleanInputFile()" wire:click="cleanInputsByModal">{{__('Cancel')}}</button>
								</div>
							</div>
						@endisset
					</div>
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
					<p>{{__('Are you sure want to delete this profile?')}}</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
					<button type="button" wire:click.prevent="delete" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Delete')}}</button>
				</div>
			</div>
		</div>
	</div>
	<div wire:ignore.self class="modal fade" id="confirmModalOldProfiles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{__('Confirm delete')}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true close-btn">×</span>
					</button>
				</div>
				<div class="modal-body text-center">
					<p>{{__('Are you sure want to delete the old profiles?')}}</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
					<button type="button" wire:click.prevent="deleteOldProfiles" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Delete')}}</button>
				</div>
			</div>
		</div>
	</div>
	<div wire:ignore.self class="modal fade" id="modalDirectoryDoesntExists" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{__('Error')}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true close-btn">×</span>
					</button>
				</div>
				<div class="modal-body text-center">
					<p>{{__("The path of the images doesn't exists")}}</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
				</div>
			</div>
		</div>
	</div>
	<div wire:ignore.self class="modal fade" id="modalEditArticle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{__('Update article')}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanInputFile()" wire:click="cleanInputsByModal">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal__form--file">
						@if($messageFileImport["success_update"]["show"])
							<div class="alert alert-success" role="alert">
								{{$messageFileImport["success_update"]["msg"]}}
							</div>
						@endif
						@if($messageFileImport["error_update"]["show"])
							<div class="alert alert-danger" role="alert">
								{{$messageFileImport["error_update"]["msg"]}}
							</div>
						@endif
						<div class="form-group">
							<label for="aboutme_update">About me</label>
							<textarea class="form-control" id="aboutme_update" rows="3" wire:model="aboutMeToUpdate"></textarea>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="province_update" class="col-form-label">{{__('Select the province')}}</label>
								<select class="form-control" id="province_update" wire:model="provinceToUpdate">
									@if(!empty($provinces))
										@foreach($provinces as $province)
											<option value={{$province->name}}>{{$province->name}}</option>
										@endforeach
									@endif
								</select>
							</div>
							<div class="form-group col-md-6">
								<label for="city_update" class="col-form-label">{{__('Select the city')}}</label>
								<select class="form-control" id="city_update" wire:model="cityToUpdate">
									@if(!empty($cities))
										@foreach($cities as $city)
											<option value={{$city->name}}>{{$city->name}}</option>
										@endforeach
									@endif
								</select>
								@error('cityToUpdate') <span class="error">{{ $message }}</span> @enderror
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Cancel')}}</button>
					<button type="button" wire:click="insertTemplate" class="btn btn-info">{{__('Update')}}</button>
				</div>
			</div>
		</div>
	</div>
	<div wire:ignore.self class="modal fade" id="modalWriteReview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">{{__('Reviews')}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanInputFile()" wire:click="cleanInputsByModal">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="reviews_by_profile mb-4">
						@if (isset($getReviewsByProfile) && count($getReviewsByProfile) >0)
						<table class="table table-hover table-templates profiles__table">
							<thead>
								<tr>
									<th class="w-5">{{__('Stars')}}</th>
									<th class="w-5">{{__('Review')}}</th>
									<th class="w-5">{{__('Writed by')}}</th>
									<th class="w-5">{{__('Action')}}</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
							@foreach ($getReviewsByProfile as $review)
								<tr>
									<td>{{ $review->stars }}</td>
									<td>{{$review->comment}}</td>
									<td>{{ $review->writted_by }}</td>
									<td>
										@if(permission('reviews_by_site', 'delete'))
											<a class="reds" wire:click="deleteReview({{$review->id}})" alt="{{__('Delete review')}}" title="{{__('Delete review')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
										@endif
									</td>
								</tr>
							@endforeach
						</table>
						@else
							<div class="text-center text-muted mt-5 mb-5"><em>{{__('There is not reviews yet')}}</em></div>
						@endif
					</div>
					<div class="modal__form--file">
						<div class="form-group">
							<label for="customRange2" class="form-label">Stars</label>
							<input type="range" min="0" max="5" oninput="this.nextElementSibling.value = this.value" wire:model.defer="stars">
							<output>{{ $stars }}</output>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="write_review">Write review</label>
								<textarea class="form-control" id="write_review" rows="3" wire:model="text_review"></textarea>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="writted_by">Writed by</label>
								<input type="text" name="" class="form-control" id="writted_by" wire:model="writted_by">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Cancel')}}</button>
					<button type="button" wire:click="insertReview" class="btn btn-info">{{__('Insert')}}</button>
				</div>
				@if($messageFileImport["success_review"]["show"])
					<div class="alert alert-success" role="alert">
						{{$messageFileImport["success_review"]["msg"]}}
					</div>
				@endif
				@if($messageFileImport["error_review"]["show"])
					<div class="alert alert-danger" role="alert">
						{{$messageFileImport["error_review"]["msg"]}}
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@push('scripts')
	<script>
		window.addEventListener('showAddProfiles', event => {
			$('#addProfiles').modal({
				backdrop: 'static',
				keyboard: false
			});
		});
		const fileSelector = document.getElementById('profiles_csv');
		function getHeadersByCsv(str, delimiter = ",") {
			return str.slice(0, str.indexOf("\n")).split(delimiter);
		}
		fileSelector.addEventListener('change', (event) => {
			const input = fileSelector.files[0];
			const reader = new FileReader();
			reader.onload = (e)=> {
				const text = e.target.result;
				const headers = JSON.stringify(getHeadersByCsv(text));
				@this.set('headers', headers);
			};
			reader.readAsText(input);
		});
		function cleanInputFile() {
			document.getElementById("profiles_csv").value = "";
		}

		window.addEventListener('confirmDelete', event => {
			$('#confirmModal').modal('show');
		});

		window.addEventListener('confirmDeleteOldProfiles', event => {
			$('#confirmModalOldProfiles').modal('show');
		});

		window.addEventListener('modalDirectoryDoesntExists', event => {
			$('#modalDirectoryDoentExists').modal('show');
		});

		window.addEventListener('modalEditArticle', event => {
			$('#modalEditArticle').modal('show');
		});
		window.addEventListener('modalWriteReview', event => {
			$('#modalWriteReview').modal('show');
		});
		
	</script>
@endpush