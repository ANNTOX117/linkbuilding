<div class="col-10 cheight">
    <div class="grid js-masonry mt-4" data-masonry='{ "itemSelector": ".grid-item", "gutter": 32, "horizontalOrder": true, "columnWidth": 280 }'>
        @if(!empty($letters))
            @foreach($letters as $letter)
                <div class="grid-card">
                    <div class="card grid-item">
                        <div class="card-header text-center border-0 px-0 py-2">
                            <div class="card-category color-box btn-block mt-4">
                                <h3 class="color-box adjust-text">{{ $letter->letter }}</h3>
                            </div>
                        </div>
                        <div class="card-body border p-4">
                            <ul class="d-flex flex-column h-100">
                                @foreach($letter->daughters as $item)
                                    <li class="color-links">
                                        <a href="{{ get_daughter($site->url, $item->url) }}" class="color-links adjust-text" alt="{{ $item->name }}">{{ $item->name }}</a>
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
