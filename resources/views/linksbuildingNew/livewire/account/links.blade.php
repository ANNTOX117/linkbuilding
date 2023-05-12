<div class="links-kopen">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				{{-- <div class="w-100 d-flex align-items-center flex-column flex-lg-row"> --}}
				<div class="w-100 row align-items-center">
					<div class="col-sm-12 my-col-md-2 col-lg-2">
						<div class="">							
							<h4 class="font-weight-bold">{{ __('View List') }}</h4>
							@if ($tab == 'articles')
								{{ $article_link->total() }}
							@endif
							@if ($tab == 'activelinks')
								{{ $link_link->total() }}
							@endif
							@if ($tab == 'aboutlinks')
								{{ $about_link->total() }}
							@endif
							@if ($tab == 'expired')
								{{ $expired_link->total() }}
							@endif
							{{ __('results found') }}
						</div>
					</div>
					{{-- <ul class="nav nav-tabs justify-content-center justify-content-lg-end border-0" id="myTab" role="tablist"> --}}
					<div class="col-sm-12 my-col-md-10 col-lg-10 tab-mylinks">
						<ul class="nav nav-tabs border-0 pb-2" id="myTab" role="tablist">
							<li class="nav-item col-auto pl-0 pr-1">
								<a class="nav-link px-3 @if($tab == 'expired') active @endif" wire:click="tab('expired')" data-toggle="tab" href="#expired-link">
									{{ __('Expired links') }}
									<small class="rounded bg-gray">{{ $all_expired }}</small>
								</a>
							</li>
							<li class="nav-item col-auto pl-0 pr-1">
								<a class="nav-link px-3 @if($tab == 'aboutlinks') active @endif" wire:click="tab('aboutlinks')" data-toggle="tab" href="#aboutlinks-link">
									{{ __('Links about to expire') }}
									<small class="rounded bg-gray">{{ $all_about }}</small>
								</a>
							</li>
							<li class="nav-item col-auto pl-0 pr-1">
								<a class="nav-link px-3 @if($tab == 'activelinks') active @endif" wire:click="tab('activelinks')" data-toggle="tab" href="#activelinks-link">
									{{ __('Active links') }}
									<small class="rounded bg-gray">{{ $all_link }}</small>
								</a>
							</li>
							<li class="nav-item col-auto pl-0 pr-1">
								<a class="nav-link px-3 @if($tab == 'articles') active @endif" wire:click="tab('articles')" data-toggle="tab" href="#articles-link">
									{{ __('Articles') }}
									<small class="rounded bg-gray">{{ $all_article }}</small>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="tab-content py-3" id="myTabContent">
					@if(\Session::has('error'))
						<div class="alert alert-danger">{{ \Session::get('error') }}</div>
						{{ \Session::forget('error') }}
					@endif
					<div class="tab-pane fade py-2 @if($tab == 'articles') show active @endif" id="articles" role="tabpanel" aria-labelledby="startpage-tab">
						<div class="box d-flex flex-column align-items-start">
							<div class="w-100">
								<div class="container-flex mb-3">
									{{-- white-container form-inline my-2 w-100 --}}
									<div class="form-inline basis-flex-15">
										{{-- col-12 col-md-3 col-lg-3 d-flex align-items-center mb-3 m-md-0 m-lg-0 justify-content-center --}}
										{{ __('Per Page:') }}&nbsp;
										<select wire:model="perPage" class="form-control select-xs inputs-small">
											<option>2</option>
											<option>5</option>
											<option>10</option>
											<option>15</option>
											<option>25</option>
										</select>
									</div>
									<div class="basis-flex-10">
										{{-- col-12 col-md-3 col-lg-2 d-flex justify-content-center mb-3 m-md-0 m-lg-0 --}}
										<button class="btn btn-sm btn-dark inputs-small btn-block" wire:click="selectallarticles">
											{{ __('Select all')}}
										</button>
									</div>
									<div class="basis-flex-20">
										{{-- col-12 col-md-3 col-lg-4 d-flex justify-content-center mb-3 m-md-0 m-lg-0 --}}
										<select wire:model="actionarticle" class="form-control select-xs inputs-small">
											<option value="">{{__('With selected')}}</option>
											<option value="renewal">{{ __('Renew')}}</option>
										</select>
									</div>
									<div class="basis-flex-30">
										{{-- col-12 col-md-3 col-lg-3 text-center ml-auto --}}
										<input wire:model.debounce.300ms="search" class="form-control inputs-small w-100" type="text" placeholder="{{__('Search')}}...">
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-default w-100" id="articles-table" style="width: 100%;">
										<thead>
											<tr>
												<th></th>
												<th wire:click="sortBy('authority_sites.type')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Type') }} @include('includes._sort-icon',['field'=>'authority_sites.type'])
													</p>
												</th>
												<th wire:click="sortBy('keywords')" style="cursor: pointer;" >
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Anchor') }} @include('includes._sort-icon',['field'=>'keywords'])
													</p>
												</th>
												<th wire:click="sortBy('articles.external_url')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Href') }} @include('includes._sort-icon',['field'=>'articles.external_url'])
													</p>
												</th>
												<th wire:click="sortBy('name')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Section') }} @include('includes._sort-icon',['field'=>'name'])
													</p>
												</th>
												<th wire:click="sortBy('authority_sites.url')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Website') }} @include('includes._sort-icon',['field'=>'authority_sites.url'])
													</p>
												</th>
												<th wire:click="sortBy('articles.published_at')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('From') }} @include('includes._sort-icon',['field'=>'articles.published_at'])
													</p>
												</th>
												<th wire:click="sortBy('articles.visible_at')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Until') }} @include('includes._sort-icon',['field'=>'articles.visible_at'])
													</p>
												</th>
												<th wire:click="sortBy('days')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Remaining') }} @include('includes._sort-icon',['field'=>'days'])
													</p>
												</th>
											</tr>
										</thead>
										<tbody>
											@if(count($article_link) > 0)
												@foreach($article_link as $item)
													<tr>
														<td>@if(!empty($item->published_at))<input type="checkbox" value="{{ $item->id }}" wire:model="article_selected">@endif</td>
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
								<div class="container-flex mb-3">
									<div class="form-inline basis-flex-15">
										{{ __('Per Page:') }}&nbsp;
										<select wire:model="perPage" class="form-control select-xs inputs-small">
											<option>2</option>
											<option>5</option>
											<option>10</option>
											<option>15</option>
											<option>25</option>
										</select>
									</div>
									<div class="basis-flex-10">
										<button class="btn btn-sm btn-dark inputs-small btn-block" wire:click="selectalllink">
											{{ __('Select all')}}
										</button>
									</div>
									<div class="basis-flex-20">
										<select wire:model="actionlink" class="form-control select-xs inputs-small">
											<option value="">{{__('With selected')}}</option>
											<option value="renewal">{{ __('Renew')}}</option>
											<option value="follow">{{ __('Follow / Nofollow')}}</option>
										</select>
									</div>
									<div class="basis-flex-30">
										<input wire:model.debounce.300ms="search" class="form-control inputs-small w-100" type="text" placeholder="{{__('Search')}}...">
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-default" id="activelinks-table" style="width: 100%;">
										<thead>
											<tr>
												<th></th>
												<th wire:click="sortBy('type')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Type') }} @include('includes._sort-icon',['field'=>'type'])
													</p>
												</th>
												<th wire:click="sortBy('anchor')" style="cursor: pointer;" >
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Anchor') }} @include('includes._sort-icon',['field'=>'anchor'])
													</p>
												</th>
												<th wire:click="sortBy('links.url')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Href') }} @include('includes._sort-icon',['field'=>'links.href'])
													</p>
												</th>
												<th wire:click="sortBy('name')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Section') }} @include('includes._sort-icon',['field'=>'name'])
													</p>
												</th>
												<th wire:click="sortBy('authority_sites.url')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Website') }} @include('includes._sort-icon',['field'=>'authority_sites.url'])
													</p>
												</th>
												<th wire:click="sortBy('published_at')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('From') }} @include('includes._sort-icon',['field'=>'published_at'])
													</p>
												</th>
												<th wire:click="sortBy('ends_at')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Until') }} @include('includes._sort-icon',['field'=>'ends_at'])
													</p>
												</th>
												<th wire:click="sortBy('days')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Remaining') }} @include('includes._sort-icon',['field'=>'days'])
													</p>
												</th>
											</tr>
										</thead>
										<tbody>
											@if(count($link_link) > 0)
												@foreach ($link_link as $item)
													<tr>
														<td>@if(!empty($item->published_at) && $item->permanent != 1)<input type="checkbox" value="{{ $item->id }}" wire:model="link_selected">@endif</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ __('Link') }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ $item->anchor }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ $item->href }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ $item->name }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ $item->url }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ date('d-m-Y', strtotime($item->visible_at)) }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>{{ $item->permanent == 1 ? '-' : date('d-m-Y', strtotime($item->ends_at)) }}</td>
														<td @if(empty($item->published_at)) style="color: #bbb !important" @endif>@if(!empty($item->published_at)){{ $item->permanent == 1 ? 'Permanent' : $item->days }} {{ $item->permanent == 1 ? '' : plural_or_singular('day', $item->days) }} @else {{__('Pending')}} @endif</td>
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
								<div class="container-flex mb-3">
									<div class="form-inline basis-flex-15">
										{{ __('Per Page:') }}&nbsp;
										<select wire:model="perPage" class="form-control select-xs inputs-small">
											<option>2</option>
											<option>5</option>
											<option>10</option>
											<option>15</option>
											<option>25</option>
										</select>
									</div>
									<div class="basis-flex-10">
										<button class="btn btn-sm btn-dark btn-block inputs-small" wire:click="about_all">
											{{ __('Select all')}}
										</button>
									</div>

									<div class="basis-flex-20">
										<select wire:model="actionabout" class="form-control select-xs inputs-small">
											<option value="">{{__('With selected')}}</option>
											<option value="renew">{{ __('Renew')}}</option>
											<option value="follow">{{ __('Follow / Nofollow')}}</option>
										</select>
									</div>
									<div class="basis-flex-30">
										<input wire:model.debounce.300ms="search" class="form-control inputs-small w-100" type="text" placeholder="{{__('Search')}}...">
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-default" id="aboutlinks-table" style="width: 100%;">
										<thead>
											<tr>
												<th></th>
												<th wire:click="sortBy('type')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Type') }} @include('includes._sort-icon',['field'=>'type'])
													</p>
												</th>
												<th wire:click="sortBy('anchor')" style="cursor: pointer;" >
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Anchor') }} @include('includes._sort-icon',['field'=>'anchor'])
													</p>
												</th>
												<th wire:click="sortBy('links.href')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Href') }} @include('includes._sort-icon',['field'=>'links.href'])
													</p>
												</th>
												<th wire:click="sortBy('name')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Section') }} @include('includes._sort-icon',['field'=>'name'])
													</p>
												</th>
												<th wire:click="sortBy('authority_sites.url')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Website') }} @include('includes._sort-icon',['field'=>'authority_sites.url'])
													</p>
												</th>
												<th wire:click="sortBy('published_at')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('From') }} @include('includes._sort-icon',['field'=>'published_at'])
													</p>
												</th>
												<th wire:click="sortBy('ends_at')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Until') }} @include('includes._sort-icon',['field'=>'ends_at'])
													</p>
												</th>
												<th wire:click="sortBy('days')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Remaining') }} @include('includes._sort-icon',['field'=>'days'])
													</p>
												</th>
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
								<div class="container-flex mb-3">
									<div class="form-inline basis-flex-15">
										{{ __('Per Page:') }}&nbsp;
										<select wire:model="perPage" class="form-control select-xs inputs-small">
											<option>2</option>
											<option>5</option>
											<option>10</option>
											<option>15</option>
											<option>25</option>
										</select>
									</div>

									<div class="basis-flex-10">
										<button class="btn btn-sm btn-dark btn-block inputs-small" wire:click="selectallexpired">
											{{ __('Select all')}}
										</button>
									</div>

									<div class="basis-flex-20">
										<select wire:model="actionexpired" class="form-control select-xs inputs-small">
											<option value="">{{__('With selected')}}</option>
											<option value="renew">{{ __('Renew')}}</option>
											<option value="follow">{{ __('Follow / Nofollow')}}</option>
										</select>
									</div>

									<div class="basis-flex-30">
										<input wire:model.debounce.300ms="search" class="form-control inputs-small w-100" type="text" placeholder="{{__('Search')}}...">
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-default" id="expired-table" style="width: 100%;">
										<thead>
											<tr>
												<th></th>
												<th wire:click="sortBy('type')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Type') }} @include('includes._sort-icon',['field'=>'type'])
													</p>
												</th>
												<th wire:click="sortBy('anchor')" style="cursor: pointer;" >
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Anchor') }} @include('includes._sort-icon',['field'=>'anchor'])
													</p>
												</th>
												<th wire:click="sortBy('links.href')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Href') }} @include('includes._sort-icon',['field'=>'links.href'])
													</p>
												</th>
												<th wire:click="sortBy('name')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Section') }} @include('includes._sort-icon',['field'=>'name'])
													</p>
												</th>
												<th wire:click="sortBy('authority_sites.url')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Website') }} @include('includes._sort-icon',['field'=>'authority_sites.url'])
													</p>
												</th>
												<th wire:click="sortBy('published_at')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('From') }} @include('includes._sort-icon',['field'=>'published_at'])
													</p>
												</th>
												<th wire:click="sortBy('ends_at')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Until') }} @include('includes._sort-icon',['field'=>'ends_at'])
													</p>
												</th>
												<th wire:click="sortBy('days')" style="cursor: pointer;">
													<p class="font-weight-bold m-0 d-flex align-items-center">
														{{ __('Remaining') }} @include('includes._sort-icon',['field'=>'days'])
													</p>
												</th>
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


