@push("styles")
    {{-- <link rel="stylesheet" href="{{'/linksbuildingNew/css/templates/datingTemplateProfile1/templates/region.css'}}"> --}}
@endpush
<div>
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
    <section class="section section--cities">
        <div class="container">
            @if (!empty($categoryMetadata->header))
                <div id="daughter_home_header">  
                    {!! $categoryMetadata->header !!}
                </div>
            @endif
            <h3 class="title title--section" data-aos="fade-up">
                <i class="fas fa-tag"></i> {{__($categoryUrl->name)}}
            </h3>
            @if (count($ProfilesByCategory)>0)
                <div class="city">
                    <div class="grid">
                        @foreach ($ProfilesByCategory as $profile)
                        <div class="grid-item">
                            <div class="block">
                                <figure>
                                    <a href="{{route("interior-profile",$profile)}}">
                                        <img src="{{$profile->image}}" alt="" loading="lazy">
                                    </a>
                                </figure>
                                <div class="content">
                                    <h3 class="title title--block">
                                        <a href="{{route("interior-profile",$profile)}}">
                                            {{$profile->title}}
                                        </a>
                                    </h3>
                                    <p class="paragraph--small c-gray">
                                        {{__(strip_tags(substring_text($profile->description,55)))}}...
                                    </p>
                                    <p class="paragraph paragraph--small c-gray">
                                        @if ($seoPages > 0)
                                            <a href="{{route("seo-pages", ["article" => $profile->categoryUrl, "articleId" => $profile->categoryId, "city" => $profile->path])}}">
                                                {{$profile->name}}
                                            </a>    
                                        @else
                                            <a href="{{route("find-date",$profile->path)}}">
                                                {{$profile->path}}
                                            </a>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                {{-- <div class="d-flex justify-content-center">
                    {{$ProfilesByCategory->links()}}
                </div> --}}
                <div class="banner__large">
                    <div class="d-flex justify-content-center">
                        {{$ProfilesByCategory->links()}}
                    </div>
                </div>
                <div class="banner__compact">
                    @if ($ProfilesByCategory->lastPage() > 1)
                        <ul class="pagination justify-content-center">
                            @if ($ProfilesByCategory->currentPage() > 1)
                                <li class="p-1"><a href="{{ $ProfilesByCategory->url(1) }}">{{__("First")}}</a></li>
                                <li class="p-1"><a href="{{ $ProfilesByCategory->previousPageUrl() }}">{{__("Previous")}}</a></li>
                            @endif

                            @for ($i = max(1, $ProfilesByCategory->currentPage() - 2); $i <= min($ProfilesByCategory->lastPage(), $ProfilesByCategory->currentPage() + 2); $i++)
                                <li class="p-1 {{ ($ProfilesByCategory->currentPage() == $i) ? 'active' : '' }}">
                                    <a href="{{ $ProfilesByCategory->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($ProfilesByCategory->currentPage() < $ProfilesByCategory->lastPage())
                                <li class="p-1"><a href="{{ $ProfilesByCategory->nextPageUrl() }}">{{__("Next")}}</a></li>
                                <li class="p-1"><a href="{{ $ProfilesByCategory->url($ProfilesByCategory->lastPage()) }}">{{__("Last")}}</a></li>
                            @endif
                        </ul>
                    @endif
                </div>
                {{-- <div class="text-center">
                    <a href="{{route('category-by-city',['category'=>$categoryUrl->url])}}" class="btn btn-primary">{{__("See by Cities")}}</a>
                </div> --}}
            @else
                @if (count($randomUsers)>0)
                    <div class="city">
                        <div class="grid">
                            @foreach ($randomUsers as $profile)
                            <div class="grid-item">
                                <div class="block">
                                    <figure>
                                        <a href="{{route("interior-profile",$profile)}}">
                                            <img src="{{$profile->image}}" alt="" loading="lazy">
                                        </a>
                                    </figure>
                                    <div class="content">
                                        <h3 class="title title--block">
                                            <a href="{{route("interior-profile",$profile)}}">
                                                {{$profile->title}}
                                            </a>
                                        </h3>
                                        <p class="paragraph--small c-gray">
                                            {{__(strip_tags(substring_text($profile->description,55)))}}...
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>
    @if (!empty($categoryMetadata->footer))
    <section class="section section--content">
        <div class="container">
            <div id="daughter_home_footer">  
                {!! $categoryMetadata->footer !!}
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
    <livewire:dating-template-profile1.templates.register-footer :siteId="$site_data->id"/>
    <livewire:dating-template-profile1.templates.looking-for-contacts :idSite="$site_data->id"/>
    <livewire:dating-template-profile1.templates.footer :domain="$domain" :siteId="$site_data->id"/>
</div>
@push("scripts")
    <script>
        $("#daughter_home_header h1").addClass("title title--section");
        $("#daughter_home_header h2").addClass("title title--section");
        $("#daughter_home_header h3").addClass("title title--section");
        $("#daughter_home_header p").addClass("paragraph c-gray");
        $("#daughter_home_footer h1").addClass("title title--section");
        $("#daughter_home_footer h2").addClass("title title--section");
        $("#daughter_home_footer h3").addClass("title title--section");
        $("#daughter_home_footer p").addClass("paragraph c-gray");
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