@push("styles")
    {{-- <link rel="stylesheet" href="{{'/linksbuildingNew/css/templates/datingTemplateProfile1/templates/blog.css'}}"> --}}
@endpush
<div>
    <livewire:dating-template-profile1.templates.register-carousel :idSite="$site_data->id"/>
    <livewire:dating-template-profile1.templates.breadcrumb />
    @if(isset($bannersPC) && count($bannersPC)>0)
        <div class="mt-4">
            @php
                $pc = $this->getNextElementPC($bannersPC);
            @endphp
            <div class="banner text-center banner__large">
                <a href="{{$pc["url_redirect"]}}" target="_blank"><img src="{{$pc["url_file"]}}" alt="" loading="lazy"></a>
            </div>
        </div>
    @endif
    @if(isset($bannersMovile) && count($bannersMovile)>0)
        <div class="mt-4">
            @php
                $movile = $this->getNextElementMovile($bannersMovile);
            @endphp
            <div class="banner text-center banner__compact">
                <a href="{{$movile["url_redirect"]}}" target="_blank"><img src="{{$movile["url_file"]}}" alt="" loading="lazy"></a>
            </div>
        </div>
    @endif
    <section class="section section--blog">
        <div class="container">        
            @if(!empty($site->blog_header))
                <div id="blog_header">
                    {!! $site->blog_header !!}
                </div>
            @endif
            <h3 class="title title--blog" data-aos="fade-up">{{__("New tips & info")}}</h3>
                @if (count($blogsRandom)>0)
                    <div class="grid">
                        @foreach ($blogsRandom as $blog)
                            <div class="grid-item">
                                <div class="block">
                                    <figure class="block__figure">
                                        <a href="{{route('interior-blog',["url"=>$blog->blog_url])}}">
                                            <img class="block__figure__image" src="{{$blog->image}}" alt="" loading="lazy">
                                        </a>
                                    </figure>
                                    <div class="block__content">
                                        <h3 class="title title--block">
                                            <a href="{{route('interior-blog',$blog->blog_url)}}">
                                                {{__($blog->title)}}
                                            </a>
                                        </h3>
                                        <p class="">{{__(substring_text(strip_tags($blog->description),110))}}...</p>
                                        <p class="paragraph paragraph__small c-gray"><span style="font-size: 16px">{{__("Category")}}:</span> <a href="{{route("category",["url" => $blog->category_url])}}">{{__($blog->name)}}</a></p>
                                        <a href="{{route('interior-blog',["url"=>$blog->blog_url])}}" class="btn btn-primary">{{__("Read More")}}+</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="banner__large">
                        <div class="d-flex justify-content-center">
                            {{$blogsRandom  ->links()}}
                        </div>
                    </div>
                    <div class="banner__compact">
                        @if ($blogsRandom   ->lastPage() > 1)
                            <ul class="pagination justify-content-center">
                                @if ($blogsRandom   ->currentPage() > 1)
                                    <li class="p-1"><a href="{{ $blogsRandom->url(1) }}">{{__("First")}}</a></li>
                                    <li class="p-1"><a href="{{ $blogsRandom->previousPageUrl() }}">{{__("Previous")}}</a></li>
                                @endif

                                @for ($i = max(1, $blogsRandom  ->currentPage() - 2); $i <= min($blogsRandom  ->lastPage(), $blogsRandom->currentPage() + 2); $i++)
                                    <li class="p-1 {{ ($blogsRandom ->currentPage() == $i) ? 'active' : '' }}">
                                        <a href="{{ $blogsRandom->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if ($blogsRandom   ->currentPage() < $blogsRandom ->lastPage())
                                    <li class="p-1"><a href="{{ $blogsRandom->nextPageUrl() }}">{{__("Next")}}</a></li>
                                    <li class="p-1"><a href="{{ $blogsRandom->url($blogsRandom  ->lastPage()) }}">{{__("Last")}}</a></li>
                                @endif
                            </ul>
                        @endif
                    </div>
                @else
                    <div class="text-center my-4">
                        <h2>{{__("Blogs are coming soon")}}</h2>
                    </div>
                @endif
        </div>
    </section>
    <livewire:dating-template-profile1.templates.categories-and-province :idSite="$site_data->id"/>
        @if(!empty($site->blog_footer))
        <section class="section section--content">
            <div class="container">
                <div id="blog_footer">
                    {!! $site->blog_footer !!}  
                </div>
            </div>
        </section>
        @endif
        @if(isset($bannersPC) && count($bannersPC)>0)
            <div class="mb-4">
                @php
                    $pc = $this->getNextElementPC($bannersPC);
                @endphp
                <div class="banner text-center banner__large">
                    <a href="{{$pc["url_redirect"]}}" target="_blank"><img src="{{$pc["url_file"]}}" alt="" loading="lazy"></a>
                </div>
            </div>
        @endif
        @if(isset($bannersMovile) && count($bannersMovile)>0)
            <div class="mb-4">
                @php
                    $movile = $this->getNextElementMovile($bannersMovile);
                @endphp
                <div class="banner text-center banner__compact">
                    <a href="{{$movile["url_redirect"]}}" target="_blank"><img src="{{$movile["url_file"]}}" alt="" loading="lazy"></a>
                </div>
            </div>
        @endif
    <livewire:dating-template-profile1.templates.looking-for-contacts :idSite="$site_data->id"/>
    <livewire:dating-template-profile1.templates.footer :domain="$domain" :siteId="$site_data->id"/>
</div>
@push("scripts")
<script>
    $("#blog_header h1").addClass("title title--section");
    $("#blog_header h2").addClass("title title--section");
    $("#blog_header h3").addClass("title title--section");
    $("#blog_header p").addClass("paragraph c-gray");
    $("#blog_footer h1").addClass("title title--section");
    $("#blog_footer h2").addClass("title title--section");
    $("#blog_footer h3").addClass("title title--section");
    $("#blog_footer p").addClass("paragraph c-gray");
    document.addEventListener("reloadPage", function () {
            // init masonry
            $('.grid').masonry();
            // // destroy masonry
            $('.grid').masonry('destroy');
            $('.grid').removeData('masonry'); // This line to remove masonry's data
            // // re-init masonry again. The position will be nice
            $('.grid').masonry();
    }, false);
</script>
@endpush