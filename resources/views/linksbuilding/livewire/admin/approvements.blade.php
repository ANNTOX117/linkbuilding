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

		<div class="cont ">
			<div class="card">
				<div class="card-body">
					<div class="tab">
						<a @if($tab == 'links') class="active" @endif wire:click="table('links')">{{__('Links') }}</a>
						<a @if($tab == 'articles') class="active" @endif wire:click="table('articles')">{{__('Articles') }}</a>
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
							<table class="table table-hover table-links mb-4">
								<thead>
									<tr>
										<th>
											{{__('Site')}}
											<a wire:click="sort('links', 'site')">
												<i class="fas fa-sort"></i>
											</a>
										</th>
										<th>
											{{__('Url')}}
											<a wire:click="sort('links', 'url')">
												<i class="fas fa-sort"></i>
											</a>
										</th>
										<th>
											{{__('Text')}}
											<a wire:click="sort('links', 'anchor')">
												<i class="fas fa-sort"></i>
											</a>
										</th>
										<th>
											{{__('Follow')}}
											<a wire:click="sort('follow', 'anchor')">
												<i class="fas fa-sort"></i>
											</a>
										</th>
										{{-- <th>
											{{__('Visible')}}
											<a wire:click="sort('links', 'visible_at')">
												<i class="fas fa-sort"></i>
											</a>
										</th>
										<th>
											{{__('Expiration')}}
											<a wire:click="sort('links', 'ends_at')">
												<i class="fas fa-sort"></i>
											</a>
										</th> --}}
										<th>
											{{__('Client')}}
											<a wire:click="sort('links', 'client')">
												<i class="fas fa-sort"></i>
											</a>
										</th>
										<th>{{__('Actions')}}</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($links))
										@foreach($links as $link)
											<tr>
												<td>{{ $link->site }}</td>
												<td>{{ $link->url }}</td>
												<td>{{ $link->anchor }}</td>
												<td>@if($link->follow) <i class="fas fa-check-circle text-success"></i> @else <i class="fas fa-times-circle text-danger"></i> @endif</td>
												{{-- <td>{{ date('d-m-Y', strtotime($link->visible_at)) }}</td>
												<td>{{ date('d-m-Y', strtotime($link->ends_at)) }}</td> --}}
												<td>{{ @$link->clients->name ." ". @$link->clients->lastname }}</td>
												<td>
                                                    @if(permission('approvals', 'update'))
                                                        <a class="blues" wire:click="confirmApprove('links' , {{$link->id}})" alt="{{__('Approve Link')}}" title="{{__('Approve Link')}}">
                                                            <span class="block">
                                                                <i class="fas fa-check"></i>
                                                            </span>
                                                        </a>
                                                    @endif
												</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="6">
												<div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have Links added yet')}}</em></div>
											</td>
										</tr>
									@endif
								</tbody>
							</table>
							@if(!empty($links))
								{{ $links->links() }}
							@endif
						@endif

						@if($tab == 'articles')
							<table class="table table-hover table-articles mb-4">
								<thead>
									<th>
										{{__('Site')}}
										<a wire:click="sort('articles', 'site')">
											<i class="fas fa-sort"></i>
										</a>
									</th>
									<th>
										{{__('Url')}}
										<a wire:click="sort('articles', 'url')">
											<i class="fas fa-sort"></i>
										</a>
									</th>
									{{--<th>
										{{__('Anchor')}}
										<a wire:click="sort('articles', 'title')">
											<i class="fas fa-sort"></i>
										</a>
									</th>--}}
									 <th>
										{{__('Visible')}}
										<a wire:click="sort('articles', 'visible_at')">
											<i class="fas fa-sort"></i>
										</a>
									</th>
									<th>
										{{__('Expiration')}}
										<a wire:click="sort('articles', 'expired_at')">
											<i class="fas fa-sort"></i>
										</a>
									</th>
									<th>
										{{__('Description')}}
										<a wire:click="sort('articles', 'description')">
											<i class="fas fa-sort"></i>
										</a>
									</th>
									<th>
										{{__('Client')}}
										<a wire:click="sort('articles', 'client')">
											<i class="fas fa-sort"></i>
										</a>
									</th>
									<th>{{__('Actions')}}</th>
								</thead>
								<tbody>
									@if(!empty($articles))
										@foreach($articles as $article)
											<tr>
												<td>{{ $article->site }}</td>
												<td>{{ $article->site."/".$article->url }}</td>
												{{--<td>{{ $article->title }}</td>--}}
												<td>{{ date('d-m-Y', strtotime($article->visible_at)) }}</td>
												<td>{{ date('d-m-Y', strtotime($article->expired_at)) }}</td>
												<td class="description">{{ get_excerpt($article->description) }}</td>
												<td>{{ @$article->clients->name ." ". @$article->clients->lastname }}</td>
												<td>
                                                    @if(permission('approvals', 'update'))
                                                        <a class="blues" wire:click="confirmApprove('articles', {{$article->id}})" alt="{{__('Approve Article')}}" title="{{__('Approve Article')}}">
                                                            <span class="block">
                                                                <i class="fas fa-check"></i>
                                                            </span>
                                                        </a>
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

					</div>
				</div>
			</div>
		</div>

		<div wire:loading wire:target="sort, approve, delete">
			<img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
		</div>

		<div wire:ignore.self class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Approve payment')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p>{{__('Are you sure want to approve this Link ?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="approve" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Approve')}}</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

@push('scripts')
	<script>
		window.addEventListener('confirmApprove', event => {
			$('#approveModal').modal('show');
		});
	</script>
@endpush
