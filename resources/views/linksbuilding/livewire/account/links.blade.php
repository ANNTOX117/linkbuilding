<div class="links-kopen">
	<div class="container-fluid mt-5 px-3 px-lg-5 pt-2">
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item m-1 m-lg-2">
						<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == 'articles') active @endif" wire:click="tab('articles')" data-toggle="tab" href="#articles-link">
							{{ __('Articles') }}
							<small class="bg-secondary text-white rounded py-1 px-2 ml-1">{{ $all_article }}</small>
						</a>
					</li>
					<li class="nav-item m-1 m-lg-2">
						<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == 'activelinks') active @endif" wire:click="tab('activelinks')" data-toggle="tab" href="#activelinks-link">
							{{ __('Active links') }}
							<small class="bg-secondary text-white rounded py-1 px-2 ml-1">{{ $all_link }}</small>
					</a>
					</li>
					<li class="nav-item m-1 m-lg-2">
						<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == 'aboutlinks') active @endif" wire:click="tab('aboutlinks')" data-toggle="tab" href="#aboutlinks-link">
							{{ __('Links about to expire') }}
							<small class="bg-secondary text-white rounded py-1 px-2 ml-1">{{ $all_about }}</small>
						</a>
					</li>
					<li class="nav-item m-1 m-lg-2">
						<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == 'expired') active @endif" wire:click="tab('expired')" data-toggle="tab" href="#expired-link">
							{{ __('Expired links') }}
							<small class="bg-secondary text-white rounded py-1 px-2 ml-1">{{ $all_expired }}</small>
						</a>
					</li>
				</ul>
				<div class="tab-content py-3" id="myTabContent">
					<div class="tab-pane fade py-2 @if($tab == 'articles') show active @endif" id="articles" role="tabpanel" aria-labelledby="startpage-tab">
						<div class="box d-flex flex-column align-items-start">
							<div class="w-100">
								<div class="white-container form-inline mt-2">
									<div class="col-12 col-md-auto col-lg-auto d-flex align-items-center mb-3 m-md-0 m-lg-0">
										{{ __('Per Page:') }}&nbsp;
										<select wire:model="perPage" class="form-control select-xs inputs-small">
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
									<table class="table table-default" id="articles-table" style="width: 100%;">
										<thead>
											<tr>
												<th wire:click="sortBy('type')" style="cursor: pointer;">{{ __('Type') }} @include('includes._sort-icon',['field'=>'type'])</th>
												<th wire:click="sortBy('keywords')" style="cursor: pointer;" >{{ __('Anchor') }} @include('includes._sort-icon',['field'=>'keywords'])</th>
												<th wire:click="sortBy('articles.external_url')" style="cursor: pointer;">{{ __('Href') }} @include('includes._sort-icon',['field'=>'articles.external_url'])</th>
												<th wire:click="sortBy('name')" style="cursor: pointer;">{{ __('Section') }} @include('includes._sort-icon',['field'=>'name'])</th>
												<th wire:click="sortBy('authority_sites.url')" style="cursor: pointer;">{{ __('Website') }} @include('includes._sort-icon',['field'=>'authority_sites.url'])</th>
												<th wire:click="sortBy('articles.published_at')" style="cursor: pointer;">{{ __('From') }} @include('includes._sort-icon',['field'=>'articles.published_at'])</th>
												<th wire:click="sortBy('articles.visible_at')" style="cursor: pointer;">{{ __('Until') }} @include('includes._sort-icon',['field'=>'articles.visible_at'])</th>
												<th wire:click="sortBy('days')" style="cursor: pointer;">{{ __('Remaining') }} @include('includes._sort-icon',['field'=>'days'])</th>
											</tr>
										</thead>
										<tbody>
											@if(count($article_link) > 0)
												@foreach($article_link as $item)
													<tr>
														<td>{{ __('Article') }}</td>
														<td>{{ $item->title }}</td>
														<td>{{ $item->external_url}}</td>
														<td>{{ $item->name }}</td>
														<td>{{ $item->url }}</td>
														<td>{{ date('d-m-Y', strtotime($item->published_at)) }}</td>
														<td>{{ date('d-m-Y', strtotime($item->visible_at)) }}</td>
														<td>{{ $item->days }} {{ plural_or_singular('day', $item->days) }}</td>
													</tr>
												@endforeach
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center">
                                                        <p class="text-muted mt-3"><em>{{__('You have no items')}}</em></p>
                                                    </td>
                                                </tr>
											@endif
										</tbody>
									</table>
								</div>
								@if(!empty($article_link))
                                    {{ $article_link->links() }}
                                @endif
							</div>
						</div>
					</div>
					<div class="tab-pane fade py-2 @if($tab == 'activelinks') show active @endif" id="activelinks" role="tabpanel" aria-labelledby="sidebar-tab">
						<div class="box d-flex align-items-start">

							<div class="w-100">
								<div class="white-container form-inline mt-2">
									<div class="col-12 col-md-4 col-lg-auto d-flex align-items-center justify-content-center mb-3 m-md-0 m-lg-0">
										{{ __('Per Page:') }}&nbsp;
										<select wire:model="perPage" class="form-control select-xs inputs-small">
											<option>2</option>
											<option>5</option>
											<option>10</option>
											<option>15</option>
											<option>25</option>
										</select>
									</div>
									<div class="col-6 col-md-4 col-lg-auto d-flex justify-content-center">
										<button class="btn btn-sm btn-outline-secondary inputs-small btn-block" wire:click="selectalllink">
											{{ __('Select all')}}
										</button>
									</div>
									<div class="col-6 col-md-4 col-lg-auto d-flex justify-content-center">
										<select wire:model="actionlink" class="form-control select-xs inputs-small">
											<option value="">{{__('With selected')}}</option>
											<option value="renewal">{{ __('Renew')}}</option>
											<option value="follow">{{ __('Follow / Nofollow')}}</option>
										</select>
									</div>
									<div class="col-12 col-md-12 col-lg-auto text-center ml-auto mt-3 mt-md-3 mt-lg-0">
										<input wire:model.debounce.300ms="search" class="form-control inputs-small" type="text" placeholder="{{__('Search')}}...">
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-default" id="activelinks-table" style="width: 100%;">
										<thead>
											<tr>
												<th></th>
												<th wire:click="sortBy('type')" style="cursor: pointer;">{{ __('Type') }} @include('includes._sort-icon',['field'=>'type'])</th>
												<th wire:click="sortBy('anchor')" style="cursor: pointer;" >{{ __('Anchor') }}@include('includes._sort-icon',['field'=>'anchor'])</th>
												<th wire:click="sortBy('links.href')" style="cursor: pointer;">{{ __('Href') }}@include('includes._sort-icon',['field'=>'links.href'])</th>
												<th wire:click="sortBy('name')" style="cursor: pointer;">{{ __('Section') }}@include('includes._sort-icon',['field'=>'name'])</th>
												<th wire:click="sortBy('authority_sites.url')" style="cursor: pointer;">{{ __('Website') }}@include('includes._sort-icon',['field'=>'authority_sites.url'])</th>
												<th wire:click="sortBy('published_at')" style="cursor: pointer;">{{ __('From') }}@include('includes._sort-icon',['field'=>'published_at'])</th>
												<th wire:click="sortBy('ends_at')" style="cursor: pointer;">{{ __('Until') }}@include('includes._sort-icon',['field'=>'ends_at'])</th>
												<th wire:click="sortBy('days')" style="cursor: pointer;">{{ __('Remaining') }}@include('includes._sort-icon',['field'=>'days'])</th>
											</tr>
										</thead>
										<tbody>
											@if(count($link_link) > 0)
												@foreach ($link_link as $item)
													<tr>
														<td>@if(!empty($item->published_at))<input type="checkbox" value="{{ $item->id }}" wire:model="link_selected">@endif</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ __('Link') }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ $item->anchor }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ $item->href }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ $item->name }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ $item->url }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ date('d-m-Y', strtotime($item->visible_at)) }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ date('d-m-Y', strtotime($item->ends_at)) }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>@if(!empty($item->published_at)){{ $item->days }} {{ plural_or_singular('day', $item->days) }} @else {{__('Pending')}} @endif</td>
													</tr>
												@endforeach
                                            @else
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        <p class="text-muted mt-3"><em>{{__('You have no items')}}</em></p>
                                                    </td>
                                                </tr>
											@endif
										</tbody>
									</table>
								</div>
								@if(!empty($link_link))
									{{ $link_link->links() }}
								@endif
							</div>
						</div>
					</div>
					<div class="tab-pane fade py-2 @if($tab == 'aboutlinks') show active @endif" id="aboutlinks" role="tabpanel" aria-labelledby="content-tab">
						<div class="box d-flex align-items-start">


							<div class="w-100">
								<div class="white-container form-inline mt-2">
									<div class="col-12 col-md-4 col-lg-auto d-flex align-items-center justify-content-center mb-3 m-md-0 m-lg-0">
										{{ __('Per Page:') }}&nbsp;
										<select wire:model="perPage" class="form-control select-xs inputs-small">
											<option>2</option>
											<option>5</option>
											<option>10</option>
											<option>15</option>
											<option>25</option>
										</select>
									</div>
									<div class="col-6 col-md-4 col-lg-auto d-flex justify-content-center">
										<button class="btn btn-sm btn-outline-secondary btn-block inputs-small" wire:click="about_all">
											{{ __('Select all')}}
										</button>
									</div>

									<div class="col-6 col-md-4 col-lg-auto d-flex justify-content-center">
										<select wire:model="actionabout" class="form-control select-xs inputs-small">
											<option value="">{{__('With selected')}}</option>
											<option value="renew">{{ __('Renew')}}</option>
											<option value="follow">{{ __('Follow / Nofollow')}}</option>
										</select>
									</div>
									<div class="col-12 col-md-12 col-lg-auto text-center ml-auto mt-3 mt-md-3 mt-lg-0">
										<input wire:model.debounce.300ms="search" class="form-control inputs-small" type="text" placeholder="{{__('Search')}}...">
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-default" id="aboutlinks-table" style="width: 100%;">
										<thead>
											<tr>
												<th></th>
												<th wire:click="sortBy('type')" style="cursor: pointer;">{{ __('Type') }} @include('includes._sort-icon',['field'=>'type'])</th>
												<th wire:click="sortBy('anchor')" style="cursor: pointer;" >{{ __('Anchor') }}@include('includes._sort-icon',['field'=>'anchor'])</th>
												<th wire:click="sortBy('links.href')" style="cursor: pointer;">{{ __('Href') }}@include('includes._sort-icon',['field'=>'links.href'])</th>
												<th wire:click="sortBy('name')" style="cursor: pointer;">{{ __('Section') }}@include('includes._sort-icon',['field'=>'name'])</th>
												<th wire:click="sortBy('authority_sites.url')" style="cursor: pointer;">{{ __('Website') }}@include('includes._sort-icon',['field'=>'authority_sites.url'])</th>
												<th wire:click="sortBy('published_at')" style="cursor: pointer;">{{ __('From') }}@include('includes._sort-icon',['field'=>'published_at'])</th>
												<th wire:click="sortBy('ends_at')" style="cursor: pointer;">{{ __('Until') }}@include('includes._sort-icon',['field'=>'ends_at'])</th>
												<th wire:click="sortBy('days')" style="cursor: pointer;">{{ __('Remaining') }}@include('includes._sort-icon',['field'=>'days'])</th>
											</tr>
										</thead>
										<tbody>
											@if(count($about_link) > 0)
												@foreach($about_link as $item)
													<tr>
														<td><input type="checkbox" value="{{ $item->id }}" wire:model="about_selected"></td>
														<td>{{ __('Link') }}</td>
														<td>{{ $item->anchor }}</td>
														<td>{{ $item->href }}</td>
														<td>{{ $item->name }}</td>
														<td>{{ $item->url }}</td>
														<td>{{ date('d-m-Y', strtotime($item['visible_at'])) }}</td>
														<td>{{ date('d-m-Y', strtotime($item['ends_at'])) }}</td>
														<td>{{ $item['days'] }} {{ plural_or_singular('day', $item['days']) }}</td>
														<td class="d-none">{{ $item['follow'] }}</td>
													</tr>
												@endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        <p class="text-muted mt-3"><em>{{__('You have no items')}}</em></p>
                                                    </td>
                                                </tr>
											@endif
										</tbody>
									</table>
								</div>
                                @if(!empty($about_link))
									{{ $about_link->links() }}
								@endif
							</div>
						</div>
					</div>
					<div class="tab-pane fade py-2 @if($tab == 'expired') show active @endif" id="expired" role="tabpanel" aria-labelledby="startpage-tab">
						<div class="box d-flex align-items-start">
							<div class="w-100">
								<div class="white-container form-inline mt-2">
									<div class="col-12 col-md-4 col-lg-auto d-flex align-items-center justify-content-center mb-3 m-md-0 m-lg-0">
										{{ __('Per Page:') }}&nbsp;
										<select wire:model="perPage" class="form-control select-xs inputs-small">
											<option>2</option>
											<option>5</option>
											<option>10</option>
											<option>15</option>
											<option>25</option>
										</select>
									</div>

									<div class="col-6 col-md-4 col-lg-auto d-flex justify-content-center">
										<button class="btn btn-sm btn-outline-secondary btn-block inputs-small" wire:click="selectallexpired">
											{{ __('Select all')}}
										</button>
									</div>

									<div class="col-6 col-md-4 col-lg-auto d-flex justify-content-center">
										<select wire:model="actionexpired" class="form-control select-xs inputs-small">
											<option value="">{{__('With selected')}}</option>
											<option value="renew">{{ __('Renew')}}</option>
											<option value="follow">{{ __('Follow / Nofollow')}}</option>
										</select>
									</div>

									<div class="col-12 col-md-12 col-lg-auto text-center ml-auto mt-3 mt-md-3 mt-lg-0">
										<input wire:model.debounce.300ms="search" class="form-control inputs-small" type="text" placeholder="{{__('Search')}}...">
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-default" id="expired-table" style="width: 100%;">
										<thead>
											<tr>
												<th></th>
												<th wire:click="sortBy('type')" style="cursor: pointer;">{{ __('Type') }} @include('includes._sort-icon',['field'=>'type'])</th>
												<th wire:click="sortBy('anchor')" style="cursor: pointer;" >{{ __('Anchor') }}@include('includes._sort-icon',['field'=>'anchor'])</th>
												<th wire:click="sortBy('links.href')" style="cursor: pointer;">{{ __('Href') }}@include('includes._sort-icon',['field'=>'links.href'])</th>
												<th wire:click="sortBy('name')" style="cursor: pointer;">{{ __('Section') }}@include('includes._sort-icon',['field'=>'name'])</th>
												<th wire:click="sortBy('authority_sites.url')" style="cursor: pointer;">{{ __('Website') }}@include('includes._sort-icon',['field'=>'authority_sites.url'])</th>
												<th wire:click="sortBy('published_at')" style="cursor: pointer;">{{ __('From') }}@include('includes._sort-icon',['field'=>'published_at'])</th>
												<th wire:click="sortBy('ends_at')" style="cursor: pointer;">{{ __('Until') }}@include('includes._sort-icon',['field'=>'ends_at'])</th>
												<th wire:click="sortBy('days')" style="cursor: pointer;">{{ __('Remaining') }}@include('includes._sort-icon',['field'=>'days'])</th>
											</tr>
										</thead>
										<tbody>
											@if(count($expired_link) > 0)
												@foreach($expired_link as $item)
													<tr>
														<td><input type="checkbox" value="{{ $item->id }}" wire:model="expired_selected"></td>
														<td>{{ __('Link') }}</td>
														<td>{{ $item->anchor }}</td>
														<td>{{ $item->href }}</td>
														<td>{{ $item->name }}</td>
														<td>{{ $item->url }}</td>
														<td>{{ date('d-m-Y', strtotime($item->visible_at)) }}</td>
														<td>{{ date('d-m-Y', strtotime($item->ends_at)) }}</td>
														<td>{{ abs($item->days) }} {{ __('days ago') }}</td>
														<td class="d-none">{{ $item->follow}}</td>
													</tr>
												@endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        <p class="text-muted mt-3"><em>{{__('You have no items')}}</em></p>
                                                    </td>
                                                </tr>
											@endif
										</tbody>
									</table>
								</div>
								@if(!empty($expired_link))
                                    {{ $expired_link->links() }}
                                @endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


