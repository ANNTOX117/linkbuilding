<div>
	<div class="links-kopen">
		<div class="container-fluid mt-5 px-3 px-lg-5 pt-2">
			<div class="row">

				@if (!$invoce)
					<div class="col-md-12 my-3">
						<div class="alert alert-primary" role="alert">
							{{ __('Please provide your billing information in order to make a purchase.')}}  <a href="{{ route('customer_profile') }}">{{__('Update')}}</a>
						</div>
					</div>
				@endif

				<div class="col-md-12">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item m-1 m-lg-2">
							<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == 'startpage') active @endif" wire:click="table('startpage')" data-toggle="tab" href="#startpage-link">
								<i class="fas fa-link"></i> {{ __('Startpage link') }}
							</a>
						</li>
						<li class="nav-item m-1 m-lg-2">
							<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == 'sidebar') active @endif" wire:click="table('sidebar')" data-toggle="tab" href="#blog-sidebar-link">
								<i class="fas fa-link"></i> {{ __('Blog sidebar link') }}
							</a>
						</li>
						<li class="nav-item m-1 m-lg-2">
							<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == 'content') active @endif" wire:click="table('content')" data-toggle="tab" href="#blog-content-link">
								<i class="fas fa-link"></i> {{ __('Blog content link') }}
							</a>
						</li>
						<li class="nav-item m-1 m-lg-2">
							<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == 'article') active @endif" wire:click="table('article')" data-toggle="tab" href="#startpage-article">
								<i class="far fa-file-alt"></i> {{ __('Startpage article') }}
							</a>
						</li>
						<li class="nav-item m-1 m-lg-2">
							<a class="nav-link px-2 px-lg-3 py-lg-3 @if($tab == 'blog') active @endif" wire:click="table('blog')" data-toggle="tab" href="#blog-article">
								<i class="far fa-file-alt"></i> {{ __('Blog article') }}
							</a>
						</li>
					</ul>

					<div class="tab-content py-3" id="myTabContent">
						<div class="tab-pane fade py-2 @if($tab == 'startpage') show active @endif" id="startpage-link" role="tabpanel" aria-labelledby="startpage-tab">
							@if ($status_form_startingpage != '')
								<div class="alert @if ($status_form_startingpage == 'fail') alert-warning @else alert-success @endif " role="alert">
									<small>{{ $message_status_startingpage }}</small>
								</div>
							@endif
							<div class="box d-flex flex-column align-items-start">
								<div class="w-100 @if($startingpageactive == 'yesshow') d-none @endif">
									<div class="white-container form-inline mt-2 w-100">
										<div class="col-12 col-md-auto col-lg-auto d-flex align-items-center mb-3 m-md-0 m-lg-0">
											{{ __('Per Page:') }}&nbsp;
											<select wire:model="perPage" class="form-control select-xs inputs-small ml-2">
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
										<table class="table table-default w-100" id="domains_startingpage" style="width: 100%;">
											<thead>
												<tr>
													<th wire:click="sortBy('url')" style="cursor: pointer;">{{ __('Domain') }} @include('includes._sort-icon',['field'=>'url'])</th>
													<th wire:click="sortBy('da')" style="cursor: pointer;">{{ __('DA') }} @include('includes._sort-icon',['field'=>'da'])</th>
													<th wire:click="sortBy('pa')" style="cursor: pointer;">{{ __('PA') }} @include('includes._sort-icon',['field'=>'pa'])</th>
													<th wire:click="sortBy('tf')" style="cursor: pointer;">{{ __('TF') }} @include('includes._sort-icon',['field'=>'tf'])</th>
													<th wire:click="sortBy('cf')" style="cursor: pointer;">{{ __('CF') }} @include('includes._sort-icon',['field'=>'cf'])</th>
													<th wire:click="sortBy('subnet')" style="cursor: pointer;">{{ __('IP') }} @include('includes._sort-icon',['field'=>'subnet'])</th>
													<th wire:click="sortBy('price')" style="cursor: pointer;">{{ __('Price') }} @include('includes._sort-icon',['field'=>'price'])</th>
													<th>{{ __('Actions') }}</th>
												</tr>
											</thead>
											<tbody>
												@if(count($table_startpage_link) > 0)
                                                    @foreach ($table_startpage_link as $list_sites)
                                                    <tr>
                                                        <td>
                                                            {{ remove_http($list_sites->url) }}
                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                                <small class="offer bg-dark text-white p-2" role="alert">
                                                                    <i class="fas fa-tags"></i>
                                                                    {{ __('Offer!')}}
                                                                </small>
                                                            @endif
                                                        </td>
                                                        <td>{{ round_price($list_sites->da) }}</td>
                                                        <td>{{ round_price($list_sites->pa) }}</td>
                                                        <td>{{ round_price($list_sites->tf) }}</td>
                                                        <td>{{ round_price($list_sites->cf) }}</td>
                                                        <td>{{ $list_sites->subnet }}</td>
                                                        <td>
                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                                <del class="text-muted">
                                                                    {{ currency() }} {{ get_price($list_sites->price) }}
                                                                </del>
                                                            @else
                                                                <strong>
                                                                    {{ currency() }} {{ get_price($list_sites->price) }}
                                                                </strong>
                                                            @endif

                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                                <strong class="text-danger">
                                                                    {{ currency() }} {{ get_price($list_sites->price_special) }}
                                                                </strong>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary" wire:click="showstartingpage({{ $list_sites->id }})">
                                                                <i class="fas fa-shopping-cart"></i> {{__('Order now')}}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <p class="text-muted mt-3"><em>{{__('No links yet')}}</em></p>
                                                        </td>
                                                    </tr>
												@endif
											</tbody>
										</table>
									</div>
									@if(!empty($table_startpage_link))
									{{ $table_startpage_link->links() }}
									@endif
								</div>


								<div id="showstartingpage" class="{{ $startingpageactive }}">
									<div class="table-title mt-2 mb-4">
										<h1>{{__('Configure your link')}}</h1>
									</div>
									<div class="card card-gray">
										<div class="card-body">
											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Website')}}</label>
												<div class="col-sm-12 col-md-9">
													<div class="md-select section1" wire:ignore>
														<label class="w-100 d-flex align-items-center" for="select_startingpage_site">
															<button type="button" class="ng-binding d-flex justify-content-between m-0"></button>
														</label>
														<ul role="listbox" id="select_startingpage_site">
															@if(!empty($startpage_link_list))
                                                                @foreach($startpage_link_list as $site)
                                                                <li id="{{ $site->id }}" role="option" class="ng-binding li-starting" tabindex="-1" aria-selected="false">
                                                                    <div class="w-100 d-flex flex-column flex-md-column flex-lg-row justify-content-between content-startingpage m-0" data-site="{{ $site->id }}">
                                                                        <span class="d-flex justify-content-between">{{ $site->url }}
                                                                            @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <del class="text-muted ml-2 ml-md-2 ml-lg-3">
                                                                                {{ currency() }} {{ get_price($site->price) }}
                                                                            </del>
                                                                            @else
                                                                            <strong class="ml-3">
                                                                                {{ currency() }} {{ get_price($site->price) }}
                                                                            </strong>
                                                                            @endif

                                                                            @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <strong class="text-danger ml-3">
                                                                                {{ currency() }} {{ get_price($site->price_special) }}
                                                                            </strong>
                                                                            @endif
                                                                        </span>
                                                                        <small class="d-flex justify-content-between">
                                                                            <p class="m-0">{{ $site->subnet }}</p>
                                                                            <div>
                                                                                <strong class="mx-1">PA</strong>{{ round_price($site->pa) }}
                                                                                <strong class="mx-1">DA</strong>{{ round_price($site->da) }}
                                                                                <strong class="mx-1">TF</strong>{{ round_price($site->tf) }}
                                                                                <strong class="mx-1">CF</strong>{{ round_price($site->cf) }}
                                                                            </div>
                                                                        </small>
                                                                    </div>
                                                                </li>
                                                                @endforeach
															@endif
														</ul>
													</div>
												</div>
												@error('site_startingpage') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												<hr>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Section')}}</label>
												<div class="col-sm-12 col-md-9">
													<select class="form-control custom-select" wire:model="section_startingpage">
														<option value="">{{ __('Categories')}}</option>
														@if(!empty($categories_startingpage))
														@foreach($categories_startingpage as $cate)
														<option value="{{ $cate->id }}">{{ $cate->name }}</option>
														@endforeach
														@endif
													</select>
												</div>
												@error('section_startingpage') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												<hr>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Link URL')}}</label>
												<div class="col-sm-12  col-md-9">
													<div class="row m-0">
														<div class="col-12 col-md-6 col-lg-8 px-0">
															<input id="starting_url" wire:model="starting_url" type="text" class="form-control" placeholder="{{ __('URL') }}">
														</div>
														<div class="col col-lg px-0 mt-2 mt-md-0 mt-lg-0 w-100">
															<select id="starting_follow" wire:model="starting_follow" class="form-control custom-select">
																<option value="1">{{ __('follow') }}</option>
																<option value="2">{{ __('nofollow') }}</option>
															</select>
														</div>
                                                        <div class="col col-lg px-0 mt-2 mt-md-0 mt-lg-0 w-100">
                                                            <select id="starting_blank" wire:model="starting_blank" class="form-control custom-select">
                                                                <option value="">{{ __('Same tab') }}</option>
                                                                <option value="_blank">{{ __('New tab') }}</option>
                                                            </select>
                                                        </div>
														<div class="col-auto col-lg-1 px-0 mt-2 mt-md-0 mt-lg-0">
															<a class="btn btn-sm bg-light h-100 d-flex align-items-center justify-content-center right-icon" target="_blank" href="{{ prefix_http($starting_url) }}">
																<i class="fas fa-external-link-alt p-0 m-1"></i>
															</a>
														</div>
													</div>
												</div>
												@error('starting_url') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Anchor')}}</label>
												<div class="col-sm-12  col-md-9">
													<input wire:model="starting_anchor" type="text" class="form-control">
												</div>
                                                @error('starting_anchor') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Title')}}</label>
												<div class="col-sm-12  col-md-9">
													<input wire:model="starting_title" type="text" class="form-control" placeholder="{{ __('Optional') }}">
												</div>
                                                @error('starting_title') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Preview')}}</label>
												<div class="col-sm-12  col-md-9">
													<input type="text" class="form-control" disabled="disabled" value="{{ $starting_url_preview }}">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Publication date')}}</label>
												<div class="col-sm-12  col-md-9 input-group date">
													<input id="date_startingpage" type="text" class="form-control datepicker pl-3" placeholder="{{__('Publication date')}}"  data-provide="datepicker" data-date-format="dd/mm/yyyy" onchange="Livewire.emit('dateStartingpage', this.value)" autocomplete="off">
													<div class="input-group-addon">
														<span class="glyphicon glyphicon-th"></span>
													</div>
												</div>
												@error('expired_startingpage') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>
											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Time active')}}</label>
												<div class="col-sm-12  col-md-9">
													<select wire:model="yearstartingpage" class="form-control custom-select" id="yearsstartingpage">
														<option value="">{{__('Select the years')}}</option>
														<option value="1">{{__('1 year')}}</option>
														<option value="2">{{__('2 years')}}</option>
														<option value="3">{{__('3 years')}}</option>
														<option value="4">{{__('4 years')}}</option>
														<option value="5">{{__('5 years')}}</option>
													</select>
												</div>
												@error('yearstartingpage') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>
										</div>
										<div class="modal-footer">
											<a href="javascript:void(0)" wire:click="cancelStartingpage" class="btn btn-link mr-auto"><i class="fas fa-long-arrow-alt-left"></i> {{__('Back')}}</a>
											<a href="javascript:void(0)" wire:click="addStartingpage" class="btn btn-primary"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade py-2 @if($tab == 'sidebar') show active @endif" id="blog-sidebar-link" role="tabpanel" aria-labelledby="sidebar-tab">
							@if ($status_form_sidebar != '')
								<div class="alert @if ($status_form_sidebar == 'fail') alert-warning @else alert-success @endif " role="alert">
									<small>{{ $message_status_sidebar }}</small>
								</div>
							@endif
							<div class="box d-flex align-items-start">
								<div class="w-100 @if($linkactive == 'yesshow') d-none @endif">
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
										<table class="table table-default w-100" id="domains_links" style="width: 100%;">
											<thead>
												<tr>
													<th wire:click="sortBy('url')" style="cursor: pointer;">{{ __('Domain') }} @include('includes._sort-icon',['field'=>'url'])</th>
													<th wire:click="sortBy('da')" style="cursor: pointer;">{{ __('DA') }} @include('includes._sort-icon',['field'=>'da'])</th>
													<th wire:click="sortBy('pa')" style="cursor: pointer;">{{ __('PA') }} @include('includes._sort-icon',['field'=>'pa'])</th>
													<th wire:click="sortBy('tf')" style="cursor: pointer;">{{ __('TF') }} @include('includes._sort-icon',['field'=>'tf'])</th>
													<th wire:click="sortBy('cf')" style="cursor: pointer;">{{ __('CF') }} @include('includes._sort-icon',['field'=>'cf'])</th>
													<th wire:click="sortBy('subnet')" style="cursor: pointer;">{{ __('IP') }} @include('includes._sort-icon',['field'=>'subnet'])</th>
													<th wire:click="sortBy('price')" style="cursor: pointer;">{{ __('Price') }} @include('includes._sort-icon',['field'=>'price'])</th>
													<th>{{ __('Actions') }}</th>
												</tr>
											</thead>
											<tbody>
												@if(count($table_blog_sidebar_link) > 0)
                                                    @foreach ($table_blog_sidebar_link as $list_sites)
                                                    <tr>
                                                        <td>
                                                            {{ remove_http($list_sites->url)}}
                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                            <small class="offer bg-dark text-white p-2" role="alert">
                                                                <i class="fas fa-tags"></i>
                                                                {{ __('Offer!')}}
                                                            </small>
                                                            @endif
                                                        </td>
                                                        <td>{{ round_price($list_sites->da) }}</td>
                                                        <td>{{ round_price($list_sites->pa) }}</td>
                                                        <td>{{ round_price($list_sites->tf) }}</td>
                                                        <td>{{ round_price($list_sites->cf) }}</td>
                                                        <td>{{ $list_sites->ip }}</td>
                                                        <td>
                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                            <del class="text-muted">
                                                                {{ currency() }} {{ get_price($list_sites->price) }}
                                                            </del>
                                                            @else
                                                            <strong>
                                                                {{ currency() }} {{ get_price($list_sites->price) }}
                                                            </strong>
                                                            @endif

                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                            <strong class="text-danger">
                                                                {{ currency() }} {{ get_price($list_sites->price_special) }}
                                                            </strong>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary" wire:click="showLink({{ $list_sites->id }}, {{ $list_sites->authority }})">
                                                                <i class="fas fa-shopping-cart"></i> {{__('Order now')}}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <p class="text-muted mt-3"><em>{{__('No links yet')}}</em></p>
                                                        </td>
                                                    </tr>
												@endif
											</tbody>
										</table>

									</div>
									@if(!empty($table_blog_sidebar_link))
										{{ $table_blog_sidebar_link->links() }}
									@endif
								</div>


								<div id="showLink" class="{{ $linkactive }}">
									<div class="table-title mt-2 mb-4">
										<h1>{{__('Configure your link')}}</h1>
									</div>
									<div class="card card-gray">
										<div class="card-body">

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Website')}}</label>
												<div class="col-sm-12  col-md-9">
													<div class="md-select section2" wire:ignore>
														<label class="w-100 d-flex align-items-center" for="select_sidebar_site">
															<button type="button" class="ng-binding d-flex justify-content-between m-0"></button>
														</label>
														<ul role="listbox" id="select_sidebar_site">
															@if(!empty($blog_sidebar_list))
                                                                @foreach($blog_sidebar_list as $site)
                                                                <li id="{{ $site->id }}" role="option" class="ng-binding li-sidebar" tabindex="-1" aria-selected="false">
                                                                    <div class="w-100 d-flex flex-column flex-md-column flex-lg-row justify-content-between content-sidebar m-0" data-site="{{ $site->id }}">
                                                                        <span class="d-flex justify-content-between">{{ remove_http($site->url) }}
                                                                            @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <del class="text-muted ml-3">
                                                                                {{ currency() }} {{ get_price($site->price) }}
                                                                            </del>
                                                                            @else
                                                                            <strong class="ml-3">
                                                                                {{ currency() }} {{ get_price($site->price) }}
                                                                            </strong>
                                                                            @endif

                                                                            @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <strong class="text-danger ml-3">
                                                                                {{ currency() }} {{ get_price($site->price_special) }}
                                                                            </strong>
                                                                            @endif
                                                                        </span>
                                                                        <small class="d-flex justify-content-between">
                                                                            <p class="m-0">{{ $site->ip }}</p>
                                                                            <div>
                                                                                <strong class="mx-1">PA</strong>{{ round_price($site->pa) }}
                                                                                <strong class="mx-1">DA</strong>{{ round_price($site->da) }}
                                                                                <strong class="mx-1">TF</strong>{{ round_price($site->tf) }}
                                                                                <strong class="mx-1">CF</strong>{{ round_price($site->cf) }}
                                                                            </div>
                                                                        </small>
                                                                    </div>
                                                                </li>
                                                                @endforeach
															@endif
														</ul>
													</div>
												</div>
												@error('site_links') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												<hr>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Section')}}</label>
												<div class="col-sm-12  col-md-9">
													<select wire:model="section" class="form-control custom-select">
														@if(!empty($categories_links))
														<option value="">{{ __('Categories')}}</option>
                                                            @foreach($categories_links as $cate)
                                                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                            @endforeach
														@endif
													</select>
												</div>
												@error('section') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												<hr>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Link URL')}}</label>
												<div class="col-sm-12  col-md-9">
													<div class="row m-0">
														<div class="col-12 col-md-6 col-lg-8 px-0">
															<input id="link_url" wire:model="link_url" type="text" class="form-control" placeholder="{{ __('URL') }}">
														</div>
														<div class="col col-lg px-0 mt-2 mt-md-0 mt-lg-0 w-100">
															<select id="follow" wire:model="follow" class="form-control custom-select">
																<option value="1">{{ __('follow') }}</option>
																<option value="2">{{ __('nofollow') }}</option>
															</select>
														</div>
                                                        <div class="col col-lg px-0 mt-2 mt-md-0 mt-lg-0 w-100">
                                                            <select id="blank" wire:model="blank" class="form-control custom-select">
                                                                <option value="">{{ __('Same tab') }}</option>
                                                                <option value="_blank">{{ __('New tab') }}</option>
                                                            </select>
                                                        </div>
														<div class="col-auto col-lg-1 px-0 mt-2 mt-md-0 mt-lg-0">
															<a class="btn btn-sm bg-light h-100 d-flex align-items-center justify-content-center right-icon" target="_blank" href="{{ prefix_http($link_url) }}">
																<i class="fas fa-external-link-alt p-0 m-1"></i>
															</a>
														</div>
													</div>
												</div>
												@error('link_url') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Anchor')}}</label>
												<div class="col-sm-12  col-md-9">
													<input wire:model="anchor" type="text" class="form-control">
												</div>
                                                @error('anchor') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Title')}}</label>
												<div class="col-sm-12  col-md-9">
													<input wire:model="linktitle" type="text" class="form-control" placeholder="{{ __('Optional') }}">
												</div>
                                                @error('linktitle') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Preview')}}</label>
												<div class="col-sm-12  col-md-9">
													<input type="text" class="form-control" disabled="disabled" value="{{ $url_preview }}">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Publication date')}}</label>
												<div class="col-sm-12  col-md-9 input-group date">
													<input wire:ignore id="date_link" type="text" class="form-control datepicker pl-3" placeholder="{{__('Publication date')}}"  data-provide="datepicker" data-date-format="dd/mm/yyyy" onchange="Livewire.emit('datelink', this.value)" autocomplete="off">
													<div class="input-group-addon">
														<span class="glyphicon glyphicon-th"></span>
													</div>
												</div>
												@error('expired_link') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>
											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Time active')}}</label>
												<div class="col-sm-12  col-md-9">
													<select wire:model="yearslink" class="form-control custom-select" id="yearslink">
														<option value="">{{__('Select the years')}}</option>
														<option value="1">{{__('1 year')}}</option>
														<option value="2">{{__('2 years')}}</option>
														<option value="3">{{__('3 years')}}</option>
														<option value="4">{{__('4 years')}}</option>
														<option value="5">{{__('5 years')}}</option>
													</select>
												</div>
												@error('yearslink') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>
										</div>
										<div class="modal-footer">
											<a href="javascript:void(0)" wire:click="cancelLink" class="btn btn-link mr-auto"><i class="fas fa-long-arrow-alt-left"></i> {{__('Back')}}</a>
											<a href="javascript:void(0)" wire:click="addLink" class="btn btn-primary"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade py-2 @if($tab == 'content') show active @endif" id="blog-content-link" role="tabpanel" aria-labelledby="content-tab">
							@if ($status_form_blog != '')
							<div class="alert @if ($status_form_blog == 'fail') alert-warning @else alert-success @endif " role="alert">
								<small>{{ $message_status_blog }}</small>
							</div>
							@endif
							<div class="box d-flex align-items-start">
								<div class="w-100 @if($blogactive == 'yesshow') d-none @endif">
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
										<table class="table table-default w-100" id="domains_blog" style="width: 100%;">
											<thead>
												<tr>
													<th wire:click="sortBy('url')" style="cursor: pointer;">{{ __('Domain') }} @include('includes._sort-icon',['field'=>'url'])</th>
													<th wire:click="sortBy('da')" style="cursor: pointer;">{{ __('DA') }} @include('includes._sort-icon',['field'=>'da'])</th>
													<th wire:click="sortBy('pa')" style="cursor: pointer;">{{ __('PA') }} @include('includes._sort-icon',['field'=>'pa'])</th>
													<th wire:click="sortBy('tf')" style="cursor: pointer;">{{ __('TF') }} @include('includes._sort-icon',['field'=>'tf'])</th>
													<th wire:click="sortBy('cf')" style="cursor: pointer;">{{ __('CF') }} @include('includes._sort-icon',['field'=>'cf'])</th>
													<th wire:click="sortBy('subnet')" style="cursor: pointer;">{{ __('IP') }} @include('includes._sort-icon',['field'=>'subnet'])</th>
													<th wire:click="sortBy('price')" style="cursor: pointer;">{{ __('Price') }} @include('includes._sort-icon',['field'=>'price'])</th>
													<th>{{ __('Actions') }}</th>
												</tr>
											</thead>
											<tbody>
												@if(count($table_blog_content_link) > 0)
                                                    @foreach ($table_blog_content_link as $list_sites)
                                                    <tr>
                                                        <td>
                                                            {{ remove_http($list_sites->url) }}
                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                            <small class="offer bg-dark text-white p-2" role="alert">
                                                                <i class="fas fa-tags"></i>
                                                                {{ __('Offer!')}}
                                                            </small>
                                                            @endif
                                                        </td>
                                                        <td>{{ round_price($list_sites->da) }}</td>
                                                        <td>{{ round_price($list_sites->pa) }}</td>
                                                        <td>{{ round_price($list_sites->tf) }}</td>
                                                        <td>{{ round_price($list_sites->cf) }}</td>
                                                        <td>{{ $list_sites->subnet }}</td>
                                                        <td>
                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                            <del class="text-muted">
                                                                {{ currency() }} {{ get_price($list_sites->price) }}
                                                            </del>
                                                            @else
                                                            <strong>
                                                                {{ currency() }} {{ get_price($list_sites->price) }}
                                                            </strong>
                                                            @endif

                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                            <strong class="text-danger">
                                                                {{ currency() }} {{ get_price($list_sites->price_special) }}
                                                            </strong>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary" wire:click="showblog({{ $list_sites->id }})">
                                                                <i class="fas fa-shopping-cart"></i> {{__('Order now')}}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <p class="text-muted mt-3"><em>{{__('No links yet')}}</em></p>
                                                        </td>
                                                    </tr>
												@endif
											</tbody>
										</table>
									</div>
									@if(!empty($table_blog_content_link))
										{{ $table_blog_content_link->links() }}
									@endif
								</div>

								<div id="showCardBlog" class="{{ $blogactive }}">
									<div class="table-title mt-2 mb-4">
										<h1>{{__('Configure your link')}}</h1>
									</div>
									<div class="card card-gray">
										<div class="card-body">
											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Website')}}</label>
												<div class="col-sm-12 col-md-9">
													<div class="md-select section3" wire:ignore>
														<label class="w-100 d-flex align-items-center" for="select_blog_site">
															<button type="button" class="ng-binding d-flex justify-content-between m-0"></button>
														</label>
														<ul role="listbox" id="select_blog_site">
															@if(!empty($blog_content_list))
                                                                @foreach($blog_content_list as $site)
                                                                <li id="{{ $site->id }}" role="option" class="ng-binding li-blog" tabindex="-1" aria-selected="false">
                                                                    <div class="w-100 d-flex flex-column flex-md-column flex-lg-row justify-content-between content-blog m-0" data-site="{{ $site->id }}">
                                                                        <span class="d-flex justify-content-between">{{ remove_http($site->url) }}
                                                                            @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <del class="text-muted ml-3">
                                                                                {{ currency() }} {{ get_price($site->price) }}
                                                                            </del>
                                                                            @else
                                                                            <strong class="ml-3">
                                                                                {{ currency() }} {{ get_price($site->price) }}
                                                                            </strong>
                                                                            @endif

                                                                            @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <strong class="text-danger ml-3">
                                                                                {{ currency() }} {{ get_price($site->price_special) }}
                                                                            </strong>
                                                                            @endif
                                                                        </span>
                                                                        <small class="d-flex justify-content-between">
                                                                            <p class="m-0">{{ $site->ip }}</p>
                                                                            <div>
                                                                                <strong class="mx-1">PA</strong>{{ round_price($site->pa) }}
                                                                                <strong class="mx-1">DA</strong>{{ round_price($site->da) }}
                                                                                <strong class="mx-1">TF</strong>{{ round_price($site->tf) }}
                                                                                <strong class="mx-1">CF</strong>{{ round_price($site->cf) }}
                                                                            </div>
                                                                        </small>
                                                                    </div>
                                                                </li>
                                                                @endforeach
															@endif
														</ul>
													</div>
												</div>
												@error('blog_site') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												<hr>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Content area')}}</label>
												<div class="col-sm-12 col-md-9">
													<select wire:model="blog_section" id="blog_section" class="form-control custom-select">
														@if (!empty($blog_section_num))
                                                            @for ($i = 0; $i < $blog_section_num; $i++)
                                                            <option value="{{ $i }}">{{ __('Area ' ) }}{{ $i + 1 }}</option>
                                                            @endfor
														@endif
													</select>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Preview')}}</label>
												<div class="col-sm-12 col-md-9">
													<div id="blog_preview" class="form-control">
														{!! $blog_section_selected !!}
													</div>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Anchor text')}}</label>
												<div class="col-sm-12  col-md-9">
													<input wire:model="blog_anchor" type="text" class="form-control" placeholder="{{__('Select a word or text from the preview and build your link')}}">
												</div>
                                                @error('blog_anchor') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Href')}}</label>
												<div class="col-sm-12  col-md-9">
													<div class="row m-0">
														<div class="col-12 col-md-6 col-lg-8 px-0">
															<input id="blog_url" wire:model="blog_url" type="text" class="form-control" placeholder="{{ __('E.g. `example.com` or `https://example.com') }}">
														</div>
														<div class="col col-lg px-0 mt-2 mt-md-0 mt-lg-0 w-100">
															<select id="blog_follow" wire:model="blog_follow" class="form-control custom-select">
																<option value="1">{{ __('follow') }}</option>
																<option value="2">{{ __('nofollow') }}</option>
															</select>
														</div>
                                                        <div class="col col-lg px-0 mt-2 mt-md-0 mt-lg-0 w-100">
                                                            <select id="blog_blank" wire:model="blog_blank" class="form-control custom-select">
                                                                <option value="">{{ __('Same tab') }}</option>
                                                                <option value="_blank">{{ __('New tab') }}</option>
                                                            </select>
                                                        </div>
														<div class="col-auto col-lg-1 px-0 mt-2 mt-md-0 mt-lg-0">
															<a class="btn btn-sm bg-light h-100 d-flex align-items-center justify-content-center right-icon" target="_blank" href="{{ prefix_http($blog_url) }}">
																<i class="fas fa-external-link-alt p-0 m-1"></i>
															</a>
														</div>
													</div>
												</div>
												@error('blog_url') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Title')}}</label>
												<div class="col-sm-12  col-md-9">
													<input wire:model="blog_title" type="text" class="form-control" placeholder="{{ __('Optional') }}">
												</div>
                                                @error('blog_title') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Publication date')}}</label>
												<div class="col-sm-12  col-md-9 input-group date">
													<input wire:ignore id="date_blog" type="text" class="form-control datepicker pl-3" placeholder="{{__('Publication date')}}"  data-provide="datepicker" data-date-format="dd/mm/yyyy" onchange="Livewire.emit('dateblog', this.value)" autocomplete="off">
													<div class="input-group-addon">
														<span class="glyphicon glyphicon-th"></span>
													</div>
												</div>
												@error('blog_expired') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>
											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Time active')}}</label>
												<div class="col-sm-12  col-md-9">
													<select wire:model="blog_years" class="form-control custom-select" id="blog_years">
														<option value="">{{__('Select the years')}}</option>
														<option value="1">{{__('1 year')}}</option>
														<option value="2">{{__('2 years')}}</option>
														<option value="3">{{__('3 years')}}</option>
														<option value="4">{{__('4 years')}}</option>
														<option value="5">{{__('5 years')}}</option>
													</select>
												</div>
												@error('blog_years') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>
										</div>
										<div class="modal-footer">
											<a href="javascript:void(0)" wire:click="cancelBlog" class="btn btn-link mr-auto"><i class="fas fa-long-arrow-alt-left"></i> {{__('Back')}}</a>
											<a href="javascript:void(0)" wire:click="addBlog" class="btn btn-primary"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade py-2 @if($tab == 'article') show active @endif" id="startpage-article" role="tabpanel" aria-labelledby="startpage-tab">
							@if ($status_starting_article != '')
							<div class="alert @if ($status_starting_article == 'fail') alert-warning @else alert-success @endif " role="alert">
								<small>{{ $message_status_article }}</small>
							</div>
							@endif
							<div class="box d-flex align-items-start">

								<div class="w-100 @if($startingarticleactive == 'yesshow' or $formrequeststartpage == 'yesshow') d-none @endif">
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
										<table class="table table-default w-100" id="domains_starting_article" style="width: 100%;">
											<thead>
												<tr>
													<th wire:click="sortBy('url')" style="cursor: pointer;">{{ __('Domain') }} @include('includes._sort-icon',['field'=>'url'])</th>
													<th wire:click="sortBy('da')" style="cursor: pointer;">{{ __('DA') }} @include('includes._sort-icon',['field'=>'da'])</th>
													<th wire:click="sortBy('pa')" style="cursor: pointer;">{{ __('PA') }} @include('includes._sort-icon',['field'=>'pa'])</th>
													<th wire:click="sortBy('tf')" style="cursor: pointer;">{{ __('TF') }} @include('includes._sort-icon',['field'=>'tf'])</th>
													<th wire:click="sortBy('cf')" style="cursor: pointer;">{{ __('CF') }} @include('includes._sort-icon',['field'=>'cf'])</th>
													<th wire:click="sortBy('subnet')" style="cursor: pointer;">{{ __('IP') }} @include('includes._sort-icon',['field'=>'subnet'])</th>
													<th wire:click="sortBy('price')" style="cursor: pointer;">{{ __('Price') }} @include('includes._sort-icon',['field'=>'price'])</th>
													<th>{{ __('Actions') }}</th>
												</tr>
											</thead>
											<tbody>
												@if(count($table_startpage_article) > 0)
                                                    @foreach($table_startpage_article as $list_sites)
                                                    <tr>
                                                        <td>{{ remove_http($list_sites->url) }}</td>
                                                        <td>{{ round_price($list_sites->da) }}</td>
                                                        <td>{{ round_price($list_sites->pa) }}</td>
                                                        <td>{{ round_price($list_sites->tf) }}</td>
                                                        <td>{{ round_price($list_sites->cf) }}</td>
                                                        <td>{{ $list_sites->ip }}</td>
                                                        <td>
                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                            <del class="text-muted">
                                                                {{ currency() }} {{ get_price($list_sites->price) }}
                                                            </del>
                                                            @else
                                                            <strong>
                                                                {{ currency() }} {{ get_price($list_sites->price) }}
                                                            </strong>
                                                            @endif

                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                            <strong class="text-danger">
                                                                {{ currency() }} {{ get_price($list_sites->price_special) }}
                                                            </strong>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                @if(intval($requested_articles) == 1)
                                                                    <button class="btn btn-primary mx-1" wire:click="showRequestStartpageForm({{ $list_sites->id }})">
                                                                        <i class="fas fa-plus"></i> {{__('Request article')}}
                                                                    </button>
                                                                @endif
                                                                <button class="btn btn-primary mx-1" wire:click="showStartingarticle({{ $list_sites->id }})">
                                                                    <i class="fas fa-shopping-cart"></i> {{__('Order now')}}
                                                                </button>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <p class="text-muted mt-3"><em>{{__('No articles yet')}}</em></p>
                                                        </td>
                                                    </tr>
												@endif
											</tbody>
										</table>
									</div>
									@if(!empty($table_startpage_article))
										{{ $table_startpage_article->links() }}
									@endif
								</div>
								<div id="showStartpageArtitle" class="{{ $startingarticleactive  }}">
									<div class="table-title mt-2 mb-4">
										<h1>{{__('Configure your article')}}</h1>
									</div>
									<div class="card card-gray">
										<div class="card-body">
											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Website')}}</label>
                                                <div class="col-sm-12 col-md-9">
													<div class="md-select section4" wire:ignore>
														<label class="w-100 d-flex align-items-center" for="select_starting_article_site">
															<button type="button" class="ng-binding d-flex justify-content-between m-0"></button>
														</label>
														<ul role="listbox" id="select_starting_article_site">
															@if(!empty($startpage_article_list))
																@foreach($startpage_article_list as $site)
                                                                    <li id="{{ $site->id }}" role="option" class="ng-binding li-article" tabindex="-1" aria-selected="false">
																		<div class="w-100 d-flex flex-column flex-md-column flex-lg-row justify-content-between content-article m-0" data-site="{{ $site->id }}">
																			<span class="d-flex justify-content-between">{{ remove_http($site->url) }}
																				@if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
																				<del class="text-muted ml-3">
																					{{ currency() }} {{ get_price($site->price) }}
																				</del>
																				@else
																				<strong class="ml-3">
																					{{ currency() }} {{ get_price($site->price) }}
																				</strong>
																				@endif

                                                                                @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
																				<strong class="text-danger ml-3">
																					{{ currency() }} {{ get_price($site->price_special) }}
																				</strong>
																				@endif
																			</span>
																			<small class="d-flex justify-content-between">
																				<p class="m-0">{{ $site->ip }}</p>
																				<div>
																					<strong class="mx-1">PA</strong>{{ round_price($site->pa) }}
																					<strong class="mx-1">DA</strong>{{ round_price($site->da) }}
																					<strong class="mx-1">TF</strong>{{ round_price($site->tf) }}
																					<strong class="mx-1">CF</strong>{{ round_price($site->cf) }}
																				</div>
																			</small>
																		</div>
																	</li>
																@endforeach
															@endif
														</ul>
													</div>
												</div>
												@error('article_starting_site') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												<hr>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Category')}}</label>
												<div class="col-sm-12 col-md-9">
													<select wire:model="article_starting_selected" class="form-control custom-select">
														@if (!empty($article_starting_sections))
                                                            @foreach ($article_starting_sections as $category )
                                                            <option value="{{$category->id }}">{{ $category->name }}</option>
                                                            @endforeach
														@endif
													</select>
												</div>
												@error('article_starting_selected') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div id="content-images" class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Image')}}</label>
												<div class="col-sm-12 col-md-9">
													<div class="md-select image startingarticle {{ $list_ul_article }}">
														<label class="w-100 d-flex align-items-center" for="select_image_article">
															<button type="button" class="ng-binding d-flex justify-content-between m-0">
																@if (!empty($img_outstanding_article))
																<div class="section_select">
																	<p class="m-0 row align-items-center">
																		<span class="col-1 px-0">
																			<img class="img-fluid" src="{{ $img_outstanding_article['img'] }}">
																		</span>
																		<span class="col-auto">
																			{{ __("Author:") }} {{ $img_outstanding_article['photographer'] }}
																		</span>
																	</p>
																</div>
																@endif
															</button>
														</label>
														<ul role="listbox" id="select_image_article">
															<li id="search_imagen">
																<input wire:model="article_starting_categories_input" type="text" class="form-control">
															</li>
															<li id="list_preview">
																<ul role="listbox" id="image_preview">
																	@if (!empty($starting_article_image))
																	@foreach ($starting_article_image as $image)
																	<li class="suggestions" wire:click="changeimgarticle('{{ $image->id }}', 'list')">
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
												</div>

												<div class="form-group row">
													<label class="col-sm-12 col-md-3 col-form-label">{{__('Page title')}}</label>
													<div class="col-sm-12  col-md-9">
														<input wire:model="article_starting_title" type="text" class="form-control" placeholder="{{ __('Page title') }}">
													</div>
                                                    @error('article_starting_title') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												</div>

												<div class="form-group row">
													<label class="col-sm-12 col-md-3 col-form-label">{{__('Article URL')}}</label>
													<div class="col-sm-12 col-md-9">
														<input wire:model="article_starting_url" readonly type="text" class="form-control" placeholder="{{ __('my-article-url') }}">
													</div>
													@error('article_starting_url') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												</div>

												<div class="form-group row">
													<label class="col-sm-12 col-md-3 col-form-label">{{ __('Article content')}}</label>
													<div class="col-sm-12 col-md-9">
														<div class="mb-3" wire:ignore>
															<div x-data
															x-ref="quillEditor"
															x-init="
															quill = new Quill($refs.quillEditor, {theme: 'snow'});
															quill.on('text-change', function () {
																$dispatch('input', quill.root.innerHTML);
																@this.set('article_starting_description', $('.ql-editor').html())
															});
															"
															wire:model.lazy="article_starting_description"
															>
															{!! $article_starting_description !!}
														</div>
													</div>
												</div>
												@error('article_starting_description') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Publication date')}}</label>
												<div class="col-sm-12 col-md-9 input-group date">
													<input id="date_article_starting" type="text" class="form-control datepicker pl-3" placeholder="{{__('Publication date')}}" autocomplete="off" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" onchange="Livewire.emit('datestartingarticle', this.value)">
												</div>
												@error('expired_article') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>
											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Time active')}}</label>
												<div class="col-sm-12  col-md-9">
													<select wire:model="article_starting_years" class="form-control custom-select" id="yearsarticle">
														<option value="">{{__('Select the years')}}</option>
														<option value="1">{{__('1 year')}}</option>
														<option value="2">{{__('2 years')}}</option>
														<option value="3">{{__('3 years')}}</option>
														<option value="4">{{__('4 years')}}</option>
														<option value="5">{{__('5 years')}}</option>
													</select>
												</div>
												@error('article_starting_years') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>
										</div>
										<div class="modal-footer">
											<a href="javascript:void(0)" wire:click="cancelStartingArticle" class="btn btn-link mr-auto"><i class="fas fa-long-arrow-alt-left"></i> {{__('Back')}}</a>
											<a href="javascript:void(0)" wire:click="addStartingarticle" class="btn btn-primary"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
										</div>
									</div>
								</div>

                                <div id="showCardRequestStartpage" class="{{ $formrequeststartpage }}">
                                    <div class="table-title mt-2 mb-4">
                                        <h1>{{__('Configure your article requested')}}</h1>
                                    </div>
                                    <div class="card card-gray">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label class="col-sm-12 col-md-3 col-form-label">{{__('Website')}}</label>
                                                <div class="col-sm-12 col-md-9">
                                                    <div class="md-select section5" wire:ignore>
                                                        <label class="w-100 d-flex align-items-center" for="select_article_site">
                                                            <button type="button" class="ng-binding d-flex justify-content-between m-0"></button>
                                                        </label>
                                                        <ul role="listbox" id="select_article_site">
                                                            @if(!empty($blog_article_list))
                                                                @foreach($blog_article_list as $site)
                                                                    <li id="{{ $site->id }}" role="option" class="ng-binding li-article" tabindex="-1" aria-selected="false">
                                                                        <div class="w-100 d-flex flex-column flex-md-column flex-lg-row justify-content-between content-article m-0" data-site="{{ $site->id }}">
																	<span class="d-flex justify-content-between">{{ remove_http($site->url) }}
                                                                        @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <del class="text-muted ml-3">
																			{{ currency() }} {{ get_price($site->price) }}
																		</del>
                                                                        @else
                                                                            <strong class="ml-3">
																			{{ currency() }} {{ get_price($site->price) }}
																		</strong>
                                                                        @endif

                                                                        @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <strong class="text-danger ml-3">
																			{{ currency() }} {{ get_price($site->price_special) }}
																		</strong>
                                                                        @endif
																	</span>
                                                                            <small class="d-flex justify-content-between">
                                                                                <p class="m-0">{{ $site->ip }}</p>
                                                                                <div>
                                                                                    <strong class="mx-1">PA</strong>{{ round_price($site->pa) }}
                                                                                    <strong class="mx-1">DA</strong>{{ round_price($site->da) }}
                                                                                    <strong class="mx-1">TF</strong>{{ round_price($site->tf) }}
                                                                                    <strong class="mx-1">CF</strong>{{ round_price($site->cf) }}
                                                                                </div>
                                                                            </small>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                                @error('authority_site') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                                <hr>
                                            </div>

                                            <div class="form-group row">
                                                <label for="request_title" class="col-sm-12 col-md-3 col-form-label">{{__('Title suggestion')}}</label>
                                                <div class="col-sm-12  col-md-9">
                                                    <input wire:model="request_title" type="text" id="request_title" class="form-control">
                                                </div>
                                                @error('request_title') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-12 col-md-3 col-form-label">{{ __('Short description')}}</label>
                                                <div class="col-sm-12 col-md-9">
                                                    <div class="mb-3" wire:ignore>
                                                        <div x-data
                                                             x-ref="quillEditor"
                                                             x-init="
															quill_request_startpage = new Quill($refs.quillEditor, {theme: 'snow'});
															quill_request_startpage.on('text-change', function () {
																$dispatch('input', quill_request_startpage.root.innerHTML);
																@this.set('request_description', quill_request_startpage.root.innerHTML)
															});
															"
                                                             wire:model.lazy="request_description"
                                                        >
                                                            {!! $request_description !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                @error('request_description') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                                <div class="col-md-12 text-right">
                                                    <span id="filter_description" class="d-none alert-warning w-100"></span>
                                                </div>
                                            </div>
                                            @for($i = 0; $i < $max_links; $i++)
                                                <div class="form-group row">
                                                    <label for="request_text_{{$i}}" class="col-sm-12 col-md-3 col-form-label">{{__('Link text')}} {{($i + 1)}} @if($i > 0) <span class="text-muted">({{__('not required')}})</span> @endif</label>
                                                    <div class="col-sm-12 col-md-9">
                                                        <input wire:model="request_texts.{{$i}}" type="text" id="request_text_{{$i}}" class="form-control" @if($i == 0) required @endif>
                                                    </div>
                                                    @error('request_texts.' . $i) <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="form-group row">
                                                    <label for="request_url_{{$i}}" class="col-sm-12 col-md-3 col-form-label">{{__('URL')}} {{($i + 1)}} @if($i > 0) <span class="text-muted">({{__('not required')}})</span> @endif</label>
                                                    <div class="col-sm-12 col-md-9">
                                                        <input wire:model="request_urls.{{$i}}" type="text" id="request_url_{{$i}}" class="form-control" @if($i == 0) required @endif>
                                                    </div>
                                                    @error('request_urls.' . $i) <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                                </div>
                                            @endfor
                                            <div class="form-group row">
                                                <label class="col-sm-12 col-md-3 col-form-label">{{__('Publication date')}}</label>
                                                <div class="col-sm-12 col-md-9 input-group date">
                                                    <input id="date_article" type="text" class="form-control datepicker pl-3" placeholder="{{__('Publication date')}}" autocomplete="off" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" onchange="Livewire.emit('datearticle', this.value)">
                                                </div>
                                                @error('publication_date') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                                <div class="col-md-12 text-right">
                                                    <span id="publication_date" class="d-none alert-warning w-100"></span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="request_price" class="col-sm-12 col-md-3 col-form-label">{{__('Price per requested article')}}</label>
                                                <div class="col-sm-12  col-md-9">
                                                    <input name="request_price" type="text" id="request_price" class="form-control" value="{{ currency() }} {{ price_per_article() }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="javascript:void(0)" wire:click="cancelRequestStartpage" class="btn btn-link mr-auto"><i class="fas fa-long-arrow-alt-left"></i> {{__('Back')}}</a>
                                            <a href="javascript:void(0)" wire:click="addRequestBlog" class="btn btn-primary"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
                                        </div>
                                    </div>
                                </div>

							</div>
						</div>
						<div class="tab-pane fade py-2 @if($tab == 'blog') show active @endif" id="blog-article" role="tabpanel" aria-labelledby="article-tab">
							@if ($status_form_article != '')
							<div class="alert @if ($status_form_article == 'fail') alert-warning @else alert-success @endif " role="alert">
								<small>{{ $message_status_article }}</small>
							</div>
							@endif
							<div class="box d-flex align-items-start">
								<div class="w-100 @if($formactive == 'yesshow' or $formrequestblog == 'yesshow') d-none @endif">
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
									<div class="table-responsive ">
										<table class="table table-default w-100" id="domains_starting_article" style="width: 100%;">
											<thead>
												<tr>
													<th wire:click="sortBy('url')" style="cursor: pointer;">{{ __('Domain') }} @include('includes._sort-icon',['field'=>'url'])</th>
													<th wire:click="sortBy('da')" style="cursor: pointer;">{{ __('DA') }} @include('includes._sort-icon',['field'=>'da'])</th>
													<th wire:click="sortBy('pa')" style="cursor: pointer;">{{ __('PA') }} @include('includes._sort-icon',['field'=>'pa'])</th>
													<th wire:click="sortBy('tf')" style="cursor: pointer;">{{ __('TF') }} @include('includes._sort-icon',['field'=>'tf'])</th>
													<th wire:click="sortBy('cf')" style="cursor: pointer;">{{ __('CF') }} @include('includes._sort-icon',['field'=>'cf'])</th>
													<th wire:click="sortBy('subnet')" style="cursor: pointer;">{{ __('IP') }} @include('includes._sort-icon',['field'=>'subnet'])</th>
													<th wire:click="sortBy('price')" style="cursor: pointer;">{{ __('Price') }} @include('includes._sort-icon',['field'=>'price'])</th>
													<th>{{ __('Actions') }}</th>
												</tr>
											</thead>

											<tbody>
												@if(count($table_blog_article) > 0)
                                                    @foreach($table_blog_article as $list_sites)
                                                    <tr>
                                                        <td>{{ remove_http($list_sites->url) }}</td>
                                                        <td>{{ round_price($list_sites->da) }}</td>
                                                        <td>{{ round_price($list_sites->pa) }}</td>
                                                        <td>{{ round_price($list_sites->tf) }}</td>
                                                        <td>{{ round_price($list_sites->cf) }}</td>
                                                        <td>{{ $list_sites->ip }}</td>
                                                        <td>
                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                            <del class="text-muted">
                                                                {{ currency() }} {{ get_price($list_sites->price) }}
                                                            </del>
                                                            @else
                                                            <strong>
                                                                {{ currency() }} {{ get_price($list_sites->price) }}
                                                            </strong>
                                                            @endif

                                                            @if ((floatval($list_sites->price_special) > 0) and (floatval($list_sites->price_special) < floatval($list_sites->price)))
                                                            <strong class="text-danger">
                                                                {{ currency() }} {{ get_price($list_sites->price_special) }}
                                                            </strong>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                @if(intval($requested_articles) == 1)
                                                                    <button class="btn btn-primary mx-1" wire:click="showRequestBlogForm({{ $list_sites->id }})">
                                                                        <i class="fas fa-plus"></i> {{__('Request article')}}
                                                                    </button>
                                                                @endif
                                                                <button class="btn btn-primary mx-1" wire:click="showForm({{ $list_sites->id }})">
                                                                    <i class="fas fa-shopping-cart"></i> {{__('Order now')}}
                                                                </button>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <p class="text-muted mt-3"><em>{{__('No articles yet')}}</em></p>
                                                        </td>
                                                    </tr>
												@endif
											</tbody>
										</table>
									</div>
									@if(!empty($table_blog_article))
										{{ $table_blog_article->links() }}
									@endif
								</div>
								<div id="showCardArtitle" class="{{ $formactive }}">
									<div class="table-title mt-2 mb-4">
										<h1>{{__('Configure your article')}}</h1>
									</div>
									<div class="card card-gray">
										<div class="card-body">
											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Website')}}</label>
												<div class="col-sm-12 col-md-9">
													<div class="md-select section5" wire:ignore>
														<label class="w-100 d-flex align-items-center" for="select_article_site">
															<button type="button" class="ng-binding d-flex justify-content-between m-0"></button>
														</label>
														<ul role="listbox" id="select_article_site">
															@if(!empty($blog_article_list))
                                                                @foreach($blog_article_list as $site)
                                                                <li id="{{ $site->id }}" role="option" class="ng-binding li-article" tabindex="-1" aria-selected="false">
                                                                    <div class="w-100 d-flex flex-column flex-md-column flex-lg-row justify-content-between content-article m-0" data-site="{{ $site->id }}">
                                                                        <span class="d-flex justify-content-between">{{ remove_http($site->url) }}
                                                                            @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <del class="text-muted ml-3">
                                                                                {{ currency() }} {{ get_price($site->price) }}
                                                                            </del>
                                                                            @else
                                                                            <strong class="ml-3">
                                                                                {{ currency() }} {{ get_price($site->price) }}
                                                                            </strong>
                                                                            @endif

                                                                            @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <strong class="text-danger ml-3">
                                                                                {{ currency() }} {{ get_price($site->price_special) }}
                                                                            </strong>
                                                                            @endif
                                                                        </span>
                                                                        <small class="d-flex justify-content-between">
                                                                            <p class="m-0">{{ $site->ip }}</p>
                                                                            <div>
                                                                                <strong class="mx-1">PA</strong>{{ round_price($site->pa) }}
                                                                                <strong class="mx-1">DA</strong>{{ round_price($site->da) }}
                                                                                <strong class="mx-1">TF</strong>{{ round_price($site->tf) }}
                                                                                <strong class="mx-1">CF</strong>{{ round_price($site->cf) }}
                                                                            </div>
                                                                        </small>
                                                                    </div>
                                                                </li>
                                                                @endforeach
															@endif
														</ul>
													</div>
												</div>
												@error('authority_site') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												<hr>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Category')}}</label>
												<div class="col-sm-12 col-md-9">
													<select wire:model="categories" class="form-control custom-select" id="categories">
														@if (!empty($categories_article))
                                                            @foreach ($categories_article as $category )
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
														@endif
													</select>
												</div>
												@error('categories') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div id="content-images" class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Image')}}</label>
												<div class="col-sm-12 col-md-9">
													<div class="md-select image article {{ $list_ul }}">
														<label class="w-100 d-flex align-items-center" for="select_image_article">
															<button type="button" class="ng-binding d-flex justify-content-between m-0">
																@if (!empty($img_outstanding))
																	<div class="section_select">
																		<p class="m-0 row align-items-center">
																			<span class="col-1 px-0">
																				<img class="img-fluid" src="{{ $img_outstanding['img'] }}">
																			</span>
																			<span class="col-auto">
																				{{ __("Author:") }} {{ $img_outstanding['photographer'] }}
																			</span>
																		</p>
																	</div>
																@endif
															</button>
														</label>

														<ul role="listbox" id="select_image_article">
															<li id="search_imagen">
																<input wire:model="categories_input" type="text" class="form-control" id="searchimagen">
															</li>
															<li id="list_preview">
																<ul role="listbox" id="image_preview">
																	@if (!empty($article_image))
																		@foreach ($article_image as $image)
																		<li class="suggestions" wire:click="changeimg({{ $image->id }})">
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
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Page title')}}</label>
												<div class="col-sm-12  col-md-9">
													<input wire:model="articletitle" type="text" id="article_title" class="form-control" placeholder="{{ __('Page title') }}">
												</div>
                                                @error('articletitle') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Article URL')}}</label>
												<div class="col-sm-12 col-md-9">
													<input wire:model="article_url" readonly type="text" class="form-control" id="article_url" placeholder="{{ __('my-article-url') }}">
												</div>
												@error('article_url') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror

												<div class="col-md-12 text-right my-2">
													<span id="url_article" class="d-none alert-warning w-100"></span>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{ __('Article content')}}</label>
												<div class="col-sm-12 col-md-9">
													<div class="mb-3" wire:ignore>
														<div x-data
															x-ref="quillEditor"
															x-init="
															quill = new Quill($refs.quillEditor, {theme: 'snow'});
															quill.on('text-change', function () {
																$dispatch('input', quill.root.innerHTML);
																@this.set('description', quill.root.innerHTML)
															});
															"
															wire:model.lazy="description"
															>
															{!! $description !!}
														</div>
													</div>
												</div>
												@error('description') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												<div class="col-md-12 text-right">
													<span id="filter_description" class="d-none alert-warning w-100"></span>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Publication date')}}</label>
												<div class="col-sm-12 col-md-9 input-group date">
													<input id="date_article" type="text" class="form-control datepicker pl-3" placeholder="{{__('Publication date')}}" autocomplete="off" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" onchange="Livewire.emit('datearticle', this.value)">
												</div>
												@error('expired_article') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
												<div class="col-md-12 text-right">
													<span id="expired_article" class="d-none alert-warning w-100"></span>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-12 col-md-3 col-form-label">{{__('Time active')}}</label>
												<div class="col-sm-12  col-md-9">
													<select wire:model="yearsarticle" class="form-control custom-select" id="yearsarticle">
														<option value="">{{__('Select the years')}}</option>
														<option value="1">{{__('1 year')}}</option>
														<option value="2">{{__('2 years')}}</option>
														<option value="3">{{__('3 years')}}</option>
														<option value="4">{{__('4 years')}}</option>
														<option value="5">{{__('5 years')}}</option>
													</select>
												</div>
												@error('yearsarticle') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
											</div>
										</div>
										<div class="modal-footer">
											<a href="javascript:void(0)" wire:click="cancelPost" class="btn btn-link mr-auto"><i class="fas fa-long-arrow-alt-left"></i> {{__('Back')}}</a>
											<a href="javascript:void(0)" wire:click="addPost" class="btn btn-primary"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
										</div>
									</div>
								</div>

                                <div id="showCardRequestBlog" class="{{ $formrequestblog }}">
                                    <div class="table-title mt-2 mb-4">
                                        <h1>{{__('Configure your article requested')}}</h1>
                                    </div>
                                    <div class="card card-gray">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label class="col-sm-12 col-md-3 col-form-label">{{__('Website')}}</label>
                                                <div class="col-sm-12 col-md-9">
                                                    <div class="md-select section5" wire:ignore>
                                                        <label class="w-100 d-flex align-items-center" for="select_article_site">
                                                            <button type="button" class="ng-binding d-flex justify-content-between m-0"></button>
                                                        </label>
                                                        <ul role="listbox" id="select_article_site">
                                                            @if(!empty($blog_article_list))
                                                                @foreach($blog_article_list as $site)
                                                                    <li id="{{ $site->id }}" role="option" class="ng-binding li-article" tabindex="-1" aria-selected="false">
                                                                        <div class="w-100 d-flex flex-column flex-md-column flex-lg-row justify-content-between content-article m-0" data-site="{{ $site->id }}">
																	<span class="d-flex justify-content-between">{{ remove_http($site->url) }}
                                                                        @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <del class="text-muted ml-3">
																			{{ currency() }} {{ get_price($site->price) }}
																		</del>
                                                                        @else
                                                                            <strong class="ml-3">
																			{{ currency() }} {{ get_price($site->price) }}
																		</strong>
                                                                        @endif

                                                                        @if ((floatval($site->price_special) > 0) and (floatval($site->price_special) < floatval($site->price)))
                                                                            <strong class="text-danger ml-3">
																			{{ currency() }} {{ get_price($site->price_special) }}
																		</strong>
                                                                        @endif
																	</span>
                                                                            <small class="d-flex justify-content-between">
                                                                                <p class="m-0">{{ $site->ip }}</p>
                                                                                <div>
                                                                                    <strong class="mx-1">PA</strong>{{ round_price($site->pa) }}
                                                                                    <strong class="mx-1">DA</strong>{{ round_price($site->da) }}
                                                                                    <strong class="mx-1">TF</strong>{{ round_price($site->tf) }}
                                                                                    <strong class="mx-1">CF</strong>{{ round_price($site->cf) }}
                                                                                </div>
                                                                            </small>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                                @error('authority_site') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                                <hr>
                                            </div>

                                            <div class="form-group row">
                                                <label for="request_title" class="col-sm-12 col-md-3 col-form-label">{{__('Title suggestion')}}</label>
                                                <div class="col-sm-12  col-md-9">
                                                    <input wire:model="request_title" type="text" id="request_title" class="form-control">
                                                </div>
                                                @error('request_title') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-12 col-md-3 col-form-label">{{ __('Short description')}}</label>
                                                <div class="col-sm-12 col-md-9">
                                                    <div class="mb-3" wire:ignore>
                                                        <div x-data
                                                             x-ref="quillEditor"
                                                             x-init="
															quill_request_blog = new Quill($refs.quillEditor, {theme: 'snow'});
															quill_request_blog.on('text-change', function () {
																$dispatch('input', quill_request_blog.root.innerHTML);
																@this.set('request_description', quill_request_blog.root.innerHTML)
															});
															"
                                                             wire:model.lazy="request_description"
                                                        >
                                                            {!! $request_description !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                @error('request_description') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                                <div class="col-md-12 text-right">
                                                    <span id="filter_description" class="d-none alert-warning w-100"></span>
                                                </div>
                                            </div>
                                            @for($i = 0; $i < $max_links; $i++)
                                                <div class="form-group row">
                                                    <label for="request2_text_{{$i}}" class="col-sm-12 col-md-3 col-form-label">{{__('Link text')}} {{($i + 1)}} @if($i > 0) <span class="text-muted">({{__('not required')}})</span> @endif</label>
                                                    <div class="col-sm-12 col-md-9">
                                                        <input wire:model="request_texts.{{$i}}" type="text" id="request2_text_{{$i}}" class="form-control" @if($i == 0) required @endif>
                                                    </div>
                                                    @error('request_texts.' . $i) <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="form-group row">
                                                    <label for="request2_url_{{$i}}" class="col-sm-12 col-md-3 col-form-label">{{__('URL')}} {{($i + 1)}} @if($i > 0) <span class="text-muted">({{__('not required')}})</span> @endif</label>
                                                    <div class="col-sm-12 col-md-9">
                                                        <input wire:model="request_urls.{{$i}}" type="text" id="request2_url_{{$i}}" class="form-control" @if($i == 0) required @endif>
                                                    </div>
                                                    @error('request_urls.' . $i) <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                                </div>
                                            @endfor
                                            <div class="form-group row">
                                                <label class="col-sm-12 col-md-3 col-form-label">{{__('Publication date')}}</label>
                                                <div class="col-sm-12 col-md-9 input-group date">
                                                    <input id="date_article" type="text" class="form-control datepicker pl-3" placeholder="{{__('Publication date')}}" autocomplete="off" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" onchange="Livewire.emit('datearticle', this.value)">
                                                </div>
                                                @error('publication_date') <span class="error w-100 pr-3 text-right">{{ $message }}</span> @enderror
                                                <div class="col-md-12 text-right">
                                                    <span id="publication_date" class="d-none alert-warning w-100"></span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="request_price" class="col-sm-12 col-md-3 col-form-label">{{__('Price per requested article')}}</label>
                                                <div class="col-sm-12  col-md-9">
                                                    <input name="request_price" type="text" id="request_price" class="form-control" value="{{ currency() }} {{ price_per_article() }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="javascript:void(0)" wire:click="cancelRequestBlog" class="btn btn-link mr-auto"><i class="fas fa-long-arrow-alt-left"></i> {{__('Back')}}</a>
                                            <a href="javascript:void(0)" wire:click="addRequestBlog" class="btn btn-primary"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
                                        </div>
                                    </div>
                                </div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script>
	$(document).ready( function () {
		$("#date_link ,#date_article ,#date_startingpage ,#date_article_starting, #date_blog").datepicker({
			startDate: '+1d',
			format: 'dd/mm/yyyy'
		});
	});
	(function($){
		window.addEventListener('showFormcontent', event => {

			var value_select_startingpage = @this.get('startingpage_default');
			var value_select_link = @this.get('link_default');
			var value_select_article = @this.get('article_default');

			var value_select_article_starting = @this.get('article_starting_default');

			var value_select_blog = @this.get('blog_default');
			var categories = @this.get('categories_article');
			var blog_section = @this.get('blog_section');

			$('#site_startingpage option:selected').removeAttr('selected');
			$('#site_links option:selected').removeAttr('selected');
			$('#article_links option:selected').removeAttr('selected');
			$('#date_startingpage').val('');
			$('#date_startingpage').datepicker('setDate', null);
			$('#date_link').val('');
			$('#date_link').datepicker('setDate', null);
			$('#date_article').val('');
			$('#date_article').datepicker('setDate', null);
			$('#date_blog').val('');
			$('#date_blog').datepicker('setDate', null);
			$('#date_article_starting').val('');
			$('#date_article_starting').datepicker('setDate', null);


			if (value_select_startingpage != '') {
				var section1 = $('#select_startingpage_site').find('#'+value_select_startingpage);
				$('.md-select.section1 ul li').not(section1).removeClass('active');
				$(section1).addClass('active');
				$('.md-select.section1 label button').html(section1.html());
			}

			if (value_select_link != '') {
				var section2 = $('#select_sidebar_site').find('#'+value_select_link);
				$('.md-select.section2 ul li').not(section2).removeClass('active');
				$(section2).addClass('active');
				$('.md-select.section2 label button').html(section2.html());
			}

			if (value_select_blog != '') {
				var section3 = $('#select_blog_site').find('#'+value_select_blog);
				$('.md-select.section3 ul li').not(section3).removeClass('active');
				$(section3).addClass('active');
				$('.md-select.section3 label button').html(section3.html());
			}

			if (value_select_article_starting != '') {
				var section4 = $('#select_starting_article_site').find('#'+value_select_article_starting);
				$('.md-select.section4 ul li').not(section4).removeClass('active');
				$(section4).addClass('active');
				$('.md-select.section4 label button').html(section4.html());
			}

			if (value_select_article != '') {
				var section5 = $('#select_article_site').find('#'+value_select_article);
				$('.md-select.section5 ul li').not(section5).removeClass('active');
				$(section5).addClass('active');
				$('.md-select.section5 label button').html(section5.html());
			}

			if (categories.length == 0) {
				$('#categories').empty().attr('disabled', true);
			}
			else{
				$('#categories').attr('disabled', false);
			}

			$('#yearsstartingpage').prop('selectedIndex',0);
			$('#yearslink').prop('selectedIndex',0);
			$('#yearsarticle').prop('selectedIndex',0);

			if(event.detail.tab == 'startpage') {
				$('#table_showstartingpage').hide();
			}

			if(event.detail.tab == 'sidebar') {
				$('#table_showLink').hide();
			}

			if(event.detail.tab == 'content') {
				$('#table_showCardBlog').hide();
			}

			if(event.detail.tab == 'article') {
				$('#table_showStartpageArtitle').hide();
			}

			if(event.detail.tab == 'blog') {
				$('#table_showCardArtitle').hide();
			}

		});

        window.addEventListener('showRequestStartpageFormcontent', event => {
            var value_select_startingpage = @this.get('startingpage_default');
            var value_select_link = @this.get('link_default');
            var value_select_article = @this.get('article_default');

            var value_select_article_starting = @this.get('article_starting_default');

            var value_select_blog = @this.get('blog_default');
            var categories = @this.get('categories_article');
            var blog_section = @this.get('blog_section');

            $('#site_startingpage option:selected').removeAttr('selected');
            $('#site_links option:selected').removeAttr('selected');
            $('#article_links option:selected').removeAttr('selected');
            $('#date_startingpage').val('');
            $('#date_startingpage').datepicker('setDate', null);
            $('#date_link').val('');
            $('#date_link').datepicker('setDate', null);
            $('#date_article').val('');
            $('#date_article').datepicker('setDate', null);
            $('#date_blog').val('');
            $('#date_blog').datepicker('setDate', null);
            $('#date_article_starting').val('');
            $('#date_article_starting').datepicker('setDate', null);


            if (value_select_startingpage != '') {
                var section1 = $('#select_startingpage_site').find('#'+value_select_startingpage);
                $('.md-select.section1 ul li').not(section1).removeClass('active');
                $(section1).addClass('active');
                $('.md-select.section1 label button').html(section1.html());
            }

            if (value_select_link != '') {
                var section2 = $('#select_sidebar_site').find('#'+value_select_link);
                $('.md-select.section2 ul li').not(section2).removeClass('active');
                $(section2).addClass('active');
                $('.md-select.section2 label button').html(section2.html());
            }

            if (value_select_blog != '') {
                var section3 = $('#select_blog_site').find('#'+value_select_blog);
                $('.md-select.section3 ul li').not(section3).removeClass('active');
                $(section3).addClass('active');
                $('.md-select.section3 label button').html(section3.html());
            }

            if (value_select_article_starting != '') {
                var section4 = $('#select_starting_article_site').find('#'+value_select_article_starting);
                $('.md-select.section4 ul li').not(section4).removeClass('active');
                $(section4).addClass('active');
                $('.md-select.section4 label button').html(section4.html());
            }

            if (value_select_article != '') {
                var section5 = $('#select_article_site').find('#'+value_select_article);
                $('.md-select.section5 ul li').not(section5).removeClass('active');
                $(section5).addClass('active');
                $('.md-select.section5 label button').html(section5.html());
            }

            if (categories.length == 0) {
                $('#categories').empty().attr('disabled', true);
            }
            else{
                $('#categories').attr('disabled', false);
            }

            $('#yearsstartingpage').prop('selectedIndex',0);
            $('#yearslink').prop('selectedIndex',0);
            $('#yearsarticle').prop('selectedIndex',0);

            if(event.detail.tab == 'startpage') {
                $('#table_showstartingpage').hide();
            }

            if(event.detail.tab == 'sidebar') {
                $('#table_showLink').hide();
            }

            if(event.detail.tab == 'content') {
                $('#table_showCardBlog').hide();
            }

            if(event.detail.tab == 'article') {
                $('#table_showStartpageArtitle').hide();
            }

            if(event.detail.tab == 'blog') {
                $('#table_showCardArtitle').hide();
            }
        });

        window.addEventListener('showRequestBlogFormcontent', event => {
            var value_select_startingpage = @this.get('startingpage_default');
            var value_select_link = @this.get('link_default');
            var value_select_article = @this.get('article_default');

            var value_select_article_starting = @this.get('article_starting_default');

            var value_select_blog = @this.get('blog_default');
            var categories = @this.get('categories_article');
            var blog_section = @this.get('blog_section');

            $('#site_startingpage option:selected').removeAttr('selected');
            $('#site_links option:selected').removeAttr('selected');
            $('#article_links option:selected').removeAttr('selected');
            $('#date_startingpage').val('');
            $('#date_startingpage').datepicker('setDate', null);
            $('#date_link').val('');
            $('#date_link').datepicker('setDate', null);
            $('#date_article').val('');
            $('#date_article').datepicker('setDate', null);
            $('#date_blog').val('');
            $('#date_blog').datepicker('setDate', null);
            $('#date_article_starting').val('');
            $('#date_article_starting').datepicker('setDate', null);


            if (value_select_startingpage != '') {
                var section1 = $('#select_startingpage_site').find('#'+value_select_startingpage);
                $('.md-select.section1 ul li').not(section1).removeClass('active');
                $(section1).addClass('active');
                $('.md-select.section1 label button').html(section1.html());
            }

            if (value_select_link != '') {
                var section2 = $('#select_sidebar_site').find('#'+value_select_link);
                $('.md-select.section2 ul li').not(section2).removeClass('active');
                $(section2).addClass('active');
                $('.md-select.section2 label button').html(section2.html());
            }

            if (value_select_blog != '') {
                var section3 = $('#select_blog_site').find('#'+value_select_blog);
                $('.md-select.section3 ul li').not(section3).removeClass('active');
                $(section3).addClass('active');
                $('.md-select.section3 label button').html(section3.html());
            }

            if (value_select_article_starting != '') {
                var section4 = $('#select_starting_article_site').find('#'+value_select_article_starting);
                $('.md-select.section4 ul li').not(section4).removeClass('active');
                $(section4).addClass('active');
                $('.md-select.section4 label button').html(section4.html());
            }

            if (value_select_article != '') {
                var section5 = $('#select_article_site').find('#'+value_select_article);
                $('.md-select.section5 ul li').not(section5).removeClass('active');
                $(section5).addClass('active');
                $('.md-select.section5 label button').html(section5.html());
            }

            if (categories.length == 0) {
                $('#categories').empty().attr('disabled', true);
            }
            else{
                $('#categories').attr('disabled', false);
            }

            $('#yearsstartingpage').prop('selectedIndex',0);
            $('#yearslink').prop('selectedIndex',0);
            $('#yearsarticle').prop('selectedIndex',0);

            if(event.detail.tab == 'startpage') {
                $('#table_showstartingpage').hide();
            }

            if(event.detail.tab == 'sidebar') {
                $('#table_showLink').hide();
            }

            if(event.detail.tab == 'content') {
                $('#table_showCardBlog').hide();
            }

            if(event.detail.tab == 'article') {
                $('#table_showStartpageArtitle').hide();
            }

            if(event.detail.tab == 'blog') {
                $('#table_showCardArtitle').hide();
            }
        });

		window.addEventListener('validateDomain', event => {
			var message = @this.get('domain_message');
			if (message == 'error_domain') {
				$('#domain').text('').text(@this.get('domain_error')).removeClass('d-none');
			}
		});

		window.addEventListener('activeSelect', event => {
			var categories = @this.get('categories_wordpress');
			if (categories.length === 0 ) {
				$('#categories').empty().attr('disabled', true);
			}
			else{
				$('#categories').attr('disabled', false);
			}
		});

		window.addEventListener('hideFormcontent', event => {
			var datepickerarticle = @this.get('expired_article');
			var description = @this.get('description');
			var imagen = @this.get('imagen');
			var categories = @this.get('categories_article');

			if (datepickerarticle == '') {
				$('#date_article').val('');
			}
			if (description == '') {
				$('.ql-editor').empty();
			}
			$('#yearslink').prop('selectedIndex',0);
			$('#yearslink').val('');
			$('#yearsarticle').prop('selectedIndex',0);
			$('#yearsarticle').val('');
			$('#imagen').val('');
			setTimeout(function () {
				$('.alert').fadeOut('slow').alert('close');
			}, 8000);


			if (categories.length == 0) {
				$('#categories').empty().attr('disabled', true);
			}
			else{
				$('#categories').attr('disabled', false);
			}


		});
		window.addEventListener('messageFilters', event => {
			var message = @this.get('message');
			$('#filter_description').addClass('d-none');
			$('#url_article').addClass('d-none');
			if (message == 'error_description') {
				$('#filter_description').text('').text(@this.get('custom_error')).removeClass('d-none');
			}
			if (message == 'error_link') {
				$('#url_article').text('').text(@this.get('custom_error')).removeClass('d-none');
			}
		});
		window.addEventListener('messageDates', event => {
			var message = @this.get('message');
			var datepickerlink = @this.get('expired_link');
			$('#expired_link').addClass('d-none');
			$('#expired_article').addClass('d-none');
			if (message == 'error_date_link') {
				$('#expired_link').text('').text(@this.get('custom_error')).removeClass('d-none');
			}
			if (message == 'error_date_article') {
				$('#expired_article').text('').text(@this.get('custom_error')).removeClass('d-none');
			}
			if (datepickerlink == '') {
				$('#date_link').val('');
			}
		});

		window.addEventListener('updatePreviewblog', event => {

		});

		window.addEventListener('doComplete', event => {
			dialogConfirm(event.detail.message, event.detail.confirm, event.detail.cancel, event.detail.redirect);
		});

	})(jQuery);
</script>

<script>

	$(document).ready(function() {
		$('#select_startingpage_site').children(':first').attr('aria-selected', true);
		$('#select_sidebar_site').children(':first').attr('aria-selected', true);
		$('#select_article_site').children(':first').attr('aria-selected', true);

		if(window.location.hash && window.location.hash == '#articles') {
			$('a[href="#startpage-article"]').click();
		}
	});

	$('.md-select.section1').on('click', function(){
		$(this).toggleClass('active')
	});

	$('.md-select.section2').on('click', function(){
		$(this).toggleClass('active')
	});

	$('.md-select.section3').on('click', function(){
		$(this).toggleClass('active')
	});

	$('.md-select.section4').on('click', function(){
		$(this).toggleClass('active')
	});

	$('.md-select.section5').on('click', function(){
		$(this).toggleClass('active')
	});

	$('.md-select.image.article label').on('click', function(){
		window.livewire.emit('changeimg' , null);
	});

	$('.md-select.image.startingarticle label').on('click', function(){
		window.livewire.emit('changeimgarticle' , null);
	});

	$('.md-select.section1 ul li').on('click', function() {
		var v = $(this).html();
		$('.md-select.section1 ul li').not($(this)).removeClass('active');
		$(this).addClass('active');
		$('.md-select.section1 label button').html(v);
	});

	$('.md-select.section2 ul li').on('click', function() {
		var v = $(this).html();
		$('.md-select.section2 ul li').not($(this)).removeClass('active');
		$(this).addClass('active');
		$('.md-select.section2 label button').html(v);
	});

	$('.md-select.section3 ul li').on('click', function() {
		var v = $(this).html();
		$('.md-select.section3 ul li').not($(this)).removeClass('active');
		$(this).addClass('active');
		$('.md-select.section3 label button').html(v);
	});

	$('.md-select.section4 ul li').on('click', function() {
		var v = $(this).html();
		$('.md-select.section4 ul li').not($(this)).removeClass('active');
		$(this).addClass('active');
		$('.md-select.section4 label button').html(v);
	});

	$('.md-select.section5 ul li').on('click', function() {
		var v = $(this).html();
		$('.md-select.section5 ul li').not($(this)).removeClass('active');
		$(this).addClass('active');
		$('.md-select.section5 label button').html(v);
	});

	$(document).on('click','.li-starting', function (){
		var value = $(this).find('.content-startingpage').data('site');
		window.livewire.emit('changestartingpage', value);
	});

	$(document).on('click','.li-sidebar', function (){
		var value = $(this).find('.content-sidebar').data('site');
		window.livewire.emit('changelink', value);
	});

	$(document).on('click','.li-blog', function (){
		var value = $(this).find('.content-blog').data('site');
		window.livewire.emit('changeblog', value);
	});

	$(document).on('click','.li-article', function (){
		var value = $(this).find('.content-article').data('site');
		window.livewire.emit('changearticle', value);
	});
</script>
@endpush
