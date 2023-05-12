<div class="container-fluid justify-content-center p-0">
    <style>
        /* .grid-item{ min-width: 280px; max-width: 280px; } */
    </style>
    @if(!empty($site->daughter_header))
        <div class="offset-md-1 col-md-10 col-sm-12 my-3 text-center">{!! $site->daughter_header !!}</div>
    @endif
    <div id="fit-width" class="offset-md-1 col-md-10 col-sm-12">
        <div class="container-fluid p-0">
            <div class="row flex-direction-column mt-4">
                <div class="ordered-items w-100">
                    <div class="container-fluid">
                        <div class="fakeMasonry-loader"><div class="loader"><i class="fas fa-spinner"></i></div></div>
                        <div class="row" style="transition:.3s ease-out;opacity: 0;height: 0;">
                            <div class="col-n-1 col-xl-25 col-lg-4 col-sm-6"></div>
                            <div class="col-n-2 col-xl-25 col-lg-4 col-sm-6"></div>
                            <div class="col-n-3 col-xl-25 col-lg-4 col-sm-6"></div>
                            <div class="col-n-4 col-xl-25 col-lg-4 col-sm-6"></div>
                            <div class="col-n-5 col-xl-25 col-lg-4 col-sm-6"></div>
                        </div>
                    </div>
                </div>
                {{-- @if(!empty($letters)) --}}
                @if(count($letters) > 0)
                    <div class="unordered-items w-100 d-none">
                    @foreach($letters as $category)
                        <div class="item_cards">
                            <div class="grid-card">
                                <div class="card grid-item mt-4">
                                    <div class="text-center border-0 px-0">
                                        <div class="color-box btn-block p-1">
                                            <h3 class="color-box adjust-text mt-2 card-titles">{{ $category->letter }}</h3>
                                        </div>
                                    </div>
                                    <div class="border">
                                        <ul class="d-flex flex-column h-100 fa-ul m-0">
                                            @foreach($category->daughters as $item)
                                                <li class="color-links border-bottom pl-2 pt-3">
                                                    <i class="fa fa-angle-double-right ml-1 pl-2"></i>
                                                    <a href="{{ get_daughter($site->url, $item->url) }}" class="color-links adjust-text description-a pl-1" alt="{{ $item->name }}">{{ $item->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                @else
                    <div class="w-100 text-center mt-5">
                        <p class="empty text-muted"><em>{{__('No content')}}</em></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- <div id="fit-width" class="offset-md-1 col-sm-10">
            <div class="fakeMasonry-loader"><div class="loader"><i class="fas fa-spinner"></i></div></div>
            <div class="row" style="transition:.3s ease-out;opacity: 0;height: 0;">
                <div class="col-n-1 col-xl-25 col-lg-4 col-sm-6"></div>
                <div class="col-n-2 col-xl-25 col-lg-4 col-sm-6"></div>
                <div class="col-n-3 col-xl-25 col-lg-4 col-sm-6"></div>
                <div class="col-n-4 col-xl-25 col-lg-4 col-sm-6"></div>
                <div class="col-n-5 col-xl-25 col-lg-4 col-sm-6"></div>
            </div>
        <div class="grid js-masonry mt-2" data-masonry='{ "itemSelector": ".grid-item", "gutter": 32, "horizontalOrder": true, "isFitWidth": true }' style="visibility: hidden;">
            @if(!empty($letters))
                @foreach($letters as $letter)
                    <div class="grid-card">
                        <div class="card grid-item">
                            <div class="text-center border-0 px-0">
                                <div class="color-box btn-block mt-4 p-1">
                                    <h3 class="color-box adjust-text mt-2">{{ $letter->letter }}</h3>
                                </div>
                            </div>
                            <div class="border">
                                <ul class="d-flex flex-column h-100 fa-ul m-0">
                                    @foreach($letter->daughters as $item)
                                        <li class="color-links border-bottom pl-2 pt-3">
                                            <i class="fa fa-angle-double-right ml-1 pl-2"></i>
                                            <a href="{{ get_daughter($site->url, $item->url) }}" class="color-links adjust-text description-a pl-1" alt="{{ $item->name }}">{{ $item->name }}</a>
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
    </div> --}}
    @if(!empty($site->daughter_footer))
        <div class="offset-md-1 col-md-10 col-sm-12 my-3 text-center">{!! $site->daughter_footer !!}</div>
    @endif
</div>
@push('scripts')
    <script>
        $(document).ready(function(){
        var key = 1;
        var responsive = {
          0 : 1,
          575 : 2,
          992 : 3,
          1200 : 5
        }
        fakeMasonry();

        $(window).resize(function(){
          fakeMasonry();
        });

        window.addEventListener('paginatorPageChanged', event => {
            fakeMasonry();
            if($('.adjust-text').length) {
                $('.adjust-text').each(function(){
                    adjustTextToBackground(this);
                });
            }
        });

        function fakeMasonry(){
          key = 1;
          var wWidth = $(window).width();
          var total_elements = 1;
          $.each(responsive, function(size, elements){
            if(wWidth >= size){
              total_elements = elements;
            }
          });
          $('.ordered-items .item_cards').remove();
          $('.unordered-items .item_cards').each(function(){
            var _this = $(this);
            _this.clone().appendTo('.col-n-'+key);
            console.log(key, total_elements);
            if(key == total_elements){
              key = 0;
            }
            key++;
          });
            setTimeout(function(){
                $('.fakeMasonry-loader+.row').css('opacity', 1);
                $('.fakeMasonry-loader+.row').css('height', 'auto');
                $('.fakeMasonry-loader').remove();
            },1000)
        }

      });

    //     $(document).ready(function(){
    //     // $('.grid').css("display", "none");
    //     fakeMasonryLoader();
    //     function fakeMasonryLoader(){
    //         setTimeout(function(){
    //             $('.fakeMasonry-loader+.row').css('opacity', 1);
    //             $('.fakeMasonry-loader+.row').css('height', 'auto');
    //             $('.fakeMasonry-loader').remove();
    //             // $('.grid').css("display", "block");
    //             $('.grid').css("visibility", "visible");
    //         },2000)
    //     }
    //   });
    </script>
@endpush