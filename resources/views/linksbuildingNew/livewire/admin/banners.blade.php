@push('stylescss')
	<style>
		.image__banner{
			max-width: 200px;
			max-height: 200px;
			/* min-width: 150px; */
			/* min-height: 150px; */
		}
		.image__banner_example{
			max-width: 400px;
			max-height: 300px;
			/* min-width: 250px; */
			/* min-height: 150px; */
		}
		
	</style>
@endpush
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
						<div class="show__messages">
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
						<div class="insert__baneer">
							<div class="text-center">
								<h1>{{__("Banners")}}</h1>
							</div>
							<div class="form__insert--banner">
								<div class="row">
									<div class="form-group col-md-4">
										<label for="site_choose" class="col-form-label">{{__("Site")}}</label>
										<select class="form-control" wire:model="site" id="site_choose">
											<option value="">Select a site</option>
											@if(!empty($allSites))
												@foreach($allSites as $site)
													<option value={{$site->id}}>{{$site->name}}</option>
												@endforeach
											@endif
										</select>
										@error('site') <span class="error">{{ $message }}</span> @enderror
									</div>
									@isset($site)
									<div class="form-group col-md-4">
										<label for="page" class="col-form-label">{{__("Page")}}</label>
										<select class="form-control" wire:model="page" id="page">
											<option value=0>Home</option>
											<option value=1>Blogs</option>
											<option value=2>Profiles</option>
											<option value=3>Regions</option>
											<option value=4>Aside</option>
											<option value=5>Ads</option>
											<option value=6>Categories</option>
											<option value=7>seo-pages</option>
											<option value=8>searching</option>
										</select>
										@error('page') <span class="error">{{ $message }}</span> @enderror
									</div>
									@endisset
									<div class="form-group col-md-4">
										<label for="type_banner" class="col-form-label">{{__("Type banner")}}</label>
										<select class="form-control" wire:model="type_banner" id="type_banner">
											<option value="0">PC</option>
											<option value="1">Movile</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-4">
										<label for="images_banner" class="col-form-label">{{__('New banner')}}</label>
										<input wire:model="newImageBanner" type="file" class="form-control-file" id="images_banner" accept="image/png, image/gif, image/jpeg"/>
										@if($newImageBanner)
											<img src="{{ $newImageBanner->temporaryUrl() }}" class="image__banner_example mt-3" />
										@endif
										@error('newImageBanner') <span class="error">{{ $message }}</span> @enderror
									</div>
									<div class="form-group col-md-4" >
										<label for="banner_redirect" class="col-form-label">Banner redirect to:</label>
										<input class="form-control" wire:model.lazy="newBannerRedirect" placeholder="https://www.example.com/" id="banner_redirect">
										@error('newBannerRedirect') <span class="error">{{ $message }}</span> @enderror
									</div>
									<div class="form-group col-md-4" wire:click="insertBanner">
										<a data-toggle="modal" class="mt-auto" >
											<span class="add round btn-small reverse">
												<i class="fas fa-plus"></i>
												{{__('Add Banner')}}
											</span>
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="show__banners mt-2">
							@isset($allBannersBySite)
								<h2>Order of banners:</h2>
								<ul wire:sortable="updateBannerOrder" class="list-group">								
									@foreach ($allBannersBySite as $banner)
										<li wire:sortable.item="{{ $banner->id }}" wire:key="banner-{{ $banner->id }}" class="list-group-item d-flex justify-content-between align-items-center banners">
											<img src="{{$banner->url_file}}" alt="" class="image__banner item"> {{$banner->url_redirect}} 
											<strong class="item">Type: {{$banner->type===0?"PC":"Movile"}}</strong>
											<span class="item">
												@if(permission('seo-pages', 'delete'))
													<a wire:click="deleteBanner({{$banner->id}})" class="reds" alt="{{__('Delete profile')}}" title="{{__('Delete profile')}}"><span class="block"><i class="far fa-trash-alt"></i></span></a>
												@endif
											</span>
											<span wire:sortable.handle style="cursor: pointer;" class="item">
												<i class="fas fa-arrows-alt-v"></i>
											</span>
										</li>
									@endforeach
								</ul>
							@endisset
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
		window.addEventListener('modalDeleteSeoPage', event => {
			$('#deletePage').modal({backdrop: 'static', keyboard: false});
		});
		window.addEventListener('hideModal', event => {
			$('#deletePage').modal('hide');
		});
		window.addEventListener('modalShowError', event => {
			@this.set('textModalError', event.detail.msg)
			$('#modalError').modal({backdrop: 'static', keyboard: false});
		});
		window.addEventListener('cleanInputsFile', event => {
			const fileInput = document.getElementById('images_banner');
			fileInput.value = '';
		});
	</script>
@endpush