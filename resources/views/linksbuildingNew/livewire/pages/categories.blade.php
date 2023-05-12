<div class="container-fluid">
    <div class="row justify-content-center">
    @if(!empty($site->daughter_home_header))
        <div class="col-md-10 col-sm-12 my-3 text-center">{!! $site->daughter_home_header !!}</div>
    @endif
    <div id="fit-width" class="col-md-10 col-sm-12">
        <div class="container-fluid">
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
                <div class="unordered-items w-100 d-none">
                    @if(!empty($daughters_paginator))
                        @foreach($daughters_paginator as $daughter)
                            <div class="item_cards">
                                <div class="grid-card">
                                    <div class="card grid-item">
                                        <div class="text-center border-0 px-0">
                                            <div class="color-box btn-block mt-4 p-1">
                                                <h3 class="color-box adjust-text mt-2 card-titles">{{ $daughter->name[0] }}</h3>
                                            </div>
                                        </div>
                                        <div class="border">
                                            @if(count($daughter->links) == 0)
                                            {{-- intval($daughter->visibility) == 2 OR  --}}
                                                <div class="text-center">
                                                    <p class="text-muted p-3"><em>{{__('No links for this category')}}</em></p>
                                                </div>
                                            @else
                                                <ul class="d-flex flex-column h-100 fa-ul m-0">
                                                    @foreach($daughter->links as $item)
                                                        <li class="color-links border-bottom pl-2 pt-3">
                                                            <i class="fa fa-angle-double-right ml-1 pl-2"></i>
                                                            <a href="{{ $item->url }}" class="color-links adjust-text description-a pl-1" alt="{{ $item->alt }}" @if($item->follow) rel="follow" @else rel="nofollow" @endif @if(intval($item->blank) == 1) target="_blank" @endif>{{ $item->anchor }}</a>
                                                            @if(!empty($item->description))
                                                                <p class="m-1 pl-2 pr-2 description-links">{{ $item->description }}</p>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
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
            @if(!empty($daughters_paginator))
                @foreach ($daughters_paginator as $daughter)
                    @if(!empty($daughter) && strtolower($paginator_id['name']) == strtolower($daughter->name[0]))
                    <div class="container">
                            <div class="col-md-12">
                                <div class="text-align-center text-avatar mt-4">
                                    {{ $daughter->links->links() }}
                                </div>
                            </div>
                        </div>				
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    @if(!empty($site->daughter_home_footer))
        <div class="col-md-10 col-sm-12 my-3 text-center">{!! $site->daughter_home_footer !!}</div>
    @endif
</div>
</div>
@push('scripts')
<script>
    $(document).ready(function(){
    var key = 1;
    var responsive = {
      0 : 1,
      575 : 2,
      992 : 3,
      1200 : 5,
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
</script>
@endpush