<div class="col-10 cheight">
    <div class="grid js-masonry mt-4" data-masonry='{ "itemSelector": ".grid-item", "gutter": 32, "horizontalOrder": true, "columnWidth": 280 }'>
        @if(!empty($daughters))
            @foreach($daughters as $daughter)
                <div class="grid-card">
                    <div class="card grid-item">
                        <div class="card-header text-center border-0 px-0 py-2">
                            <div class="card-category color-box btn-block mt-4">
                                <h3 class="color-box adjust-text">{{ $daughter->name }}</h3>
                            </div>
                        </div>
                        <div class="card-body border p-4">
                            <ul class="d-flex flex-column h-100">
                                @foreach($daughter->links as $item)
                                    <li class="color-links">
                                        <a href="{{ $item->url }}" class="color-links adjust-text" alt="{{ $item->alt }}" @if($item->follow) rel="follow" @else rel="nofollow" @endif @if(intval($item->blank) == 1) target="_blank" @endif>{{ $item->anchor }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="w-100 text-center mt-5">
                <p class="empty text-muted"><em>{{__('No content')}}</em></p>
            </div>
        @endif
    </div>
</div>
