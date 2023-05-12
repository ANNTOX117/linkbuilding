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
            <div id="headerText">
                @if(!empty($site->headerText))
                    {!! $site->headerText !!}
                @endif
            </div>
            <div class="city">
                <h3 class="title title--section" data-aos="fade-up">
                    <i class="fas fa-map-marker-alt"></i> {{ucfirst($provinceName)}}
                </h3>
                @if (count($profilesByProvince)>0)
                <div class="grid">
                    @foreach ($profilesByProvince as $profile)
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
                                    {{substring_text(strip_tags($profile->description),50)}}... 
                                 </p>
                                <p class="paragraph paragraph--small c-gray">
                                    <a href="{{route("find-date",["city"=>$profile->path])}}">{{$profile->name}}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- <div class="d-flex justify-content-center">
                    {{$profilesByProvince->links()}}
                </div> --}}
                <div class="banner__large">
                    <div class="d-flex justify-content-center">
                        {{$profilesByProvince->links()}}
                    </div>
                </div>
                <div class="banner__compact">
                    @if ($profilesByProvince->lastPage() > 1)
                        <ul class="pagination justify-content-center">
                            @if ($profilesByProvince->currentPage() > 1)
                                <li class="p-1"><a href="{{ $profilesByProvince->url(1) }}">{{__("First")}}</a></li>
                                <li class="p-1"><a href="{{ $profilesByProvince->previousPageUrl() }}">{{__("Previous")}}</a></li>
                            @endif

                            @for ($i = max(1, $profilesByProvince->currentPage() - 2); $i <= min($profilesByProvince->lastPage(), $profilesByProvince->currentPage() + 2); $i++)
                                <li class="p-1 {{ ($profilesByProvince->currentPage() == $i) ? 'active' : '' }}">
                                    <a href="{{ $profilesByProvince->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($profilesByProvince->currentPage() < $profilesByProvince->lastPage())
                                <li class="p-1"><a href="{{ $profilesByProvince->nextPageUrl() }}">{{__("Next")}}</a></li>
                                <li class="p-1"><a href="{{ $profilesByProvince->url($profilesByProvince->lastPage()) }}">{{__("Last")}}</a></li>
                            @endif
                        </ul>
                    @endif
                </div>
                @else
                <div class="text-center">{{__("No users found")}}</div>
                @endif
            </div>
            {{-- <div class="row">
                <div class="col-lg-6 col-sm-12">
                    @if (!empty($randomCities))
                        @foreach ($randomCities as $city)
                            <div class="text-center p-2">
                                <a href="#">{{ucfirst($city->path)}}</a>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="col-lg-6 col-sm-12">
                    @if (!empty($getCitiesByRadio))
                        @foreach ($getCitiesByRadio as $city)
                            <div class="text-center p-2">
                                <a href="#">{{ucfirst($city->path)}}</a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div> --}}
        </div>
    </section>
    @if(!empty($site->footerText))
        <section class="section section--content">
            <div class="container">
                <div id="footerText">
                    {!! $site->footerText !!}
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
    <livewire:dating-template-profile1.templates.footer :domain="$domain" :siteId="$site_data->id"  />
</div>
@push('scripts')
    <script>
        $("#headerText h1").addClass("title title--section");
        $("#headerText h2").addClass("title title--section");
        $("#headerText h3").addClass("title title--section");
        $("#headerText p").addClass("paragraph c-gray");
        $("#footerText h1").addClass("title title--section");
        $("#footerText h2").addClass("title title--section");
        $("#footerText h3").addClass("title title--section");
        $("#footerText p").addClass("paragraph c-gray");
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