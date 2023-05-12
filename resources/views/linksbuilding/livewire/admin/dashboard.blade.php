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
            <div class="row">
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
                    <div class="card dashboard-card">
                        <div class="card-body text-center">
                            <a href="{{ route('articles') }}">
                                <h3>{{ $articles }}</h3>
                                <p class="card-text dashboard-label">{{__('Pending articles')}}</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
                    <div class="card dashboard-card">
                        <div class="card-body text-center">
                            <a href="{{ route('links') }}">
                                <h3>{{ $links }}</h3>
                                <p class="card-text dashboard-label">{{__('Pending links')}}</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
                    <div class="card dashboard-card">
                        <div class="card-body text-center">
                            <a href="{{ route('users') }}">
                                <h3>{{ $users }}</h3>
                                <p class="card-text dashboard-label">{{__('New users')}}</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
                    <div class="card dashboard-card">
                        <div class="card-body text-center">
                            <a href="{{ route('payments') }}">
                                <h3>{{ get_price($today) }}</h3>
                                <p class="card-text dashboard-label">{{__('Payments today')}}</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
                    <div class="card dashboard-card">
                        <div class="card-body text-center">
                            <a href="{{ route('payments') }}">
                                <h3>{{ get_price($month) }}</h3>
                                <p class="card-text dashboard-label">{{__('This month')}}</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
                    <div class="card dashboard-card">
                        <div class="card-body text-center">
                            <a href="{{ route('payments') }}">
                                <h3>{{ get_price($payments) }}</h3>
                                <p class="card-text dashboard-label">{{__('Total')}}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div wire:loading wire:target="save">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>
    </div>
</div>
