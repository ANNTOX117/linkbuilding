@push("styles")
    {{-- <link rel="stylesheet" href="{{'/linksbuildingNew/css/templates/datingTemplateProfile1/templates/all-ads.css'}}"> --}}
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
    <section class="section section--ads">
        <div class="content container">
            @if(!empty($adsPageData->content_top))
                <div id="headerText">
                    <h1>{{$adsPageData->title}}</h1>
                    {!! $adsPageData->content_top !!}
                </div>
            @endif
            <div class="profiless">
                <div class="grid grid-profiles">
                    @if(!empty($randomProfiles))
                        @foreach($randomProfiles as $profile)
                            <div class="grid-item">
                                <div class="profile">
                                    <figure class="profile__figure">
                                        <a href="{{route("interior-profile",$profile)}}">
                                            <img src="{{$profile->image}}" alt="" class="profile__figure__image" loading="lazy">
                                        </a>
                                    </figure>
                                    <div class="profile__ads">
                                        <h3 class="title title--block px-3 pt-2">
                                            <a href="{{route("interior-profile",$profile)}}">
                                                {!!$profile->title!!}
                                            </a>
                                        </h3>
                                        <p class="profile__ads__paragraph px-3">
                                            {!!__(implode(' ', array_slice(explode(' ', strip_tags($profile->description)), 0, 20))).' ... '!!}
                                        </p>
                                        <br>
                                        <p class="paragraph paragraph__small--city px-3">
                                            {{__("City")}}: <a href="{{route("find-date",["city"=>$profile->cityPath])}}">{{$profile->cityName}}</a> 
                                            <span class="d-block">{{__("Province")}}: <a href="{{route("regions.name",["name"=>$profile->provincieUrl])}}">({{$profile->provincieName}})</a></span>
                                        </p>
                                    </div>
                                    <div class="profile__bottom">
                                        <a href="{{route("interior-profile",$profile)}}" class="btn btn-secondary">
                                            {{__("Respond now!")}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="banner__large">
                    <div class="d-flex justify-content-center">
                        {{$randomProfiles->links()}}
                    </div>
                </div>
                <div class="banner__compact">
                    @if ($randomProfiles->lastPage() > 1)
                        <ul class="pagination justify-content-center">
                            @if ($randomProfiles->currentPage() > 1)
                                <li class="p-1"><a href="{{ $randomProfiles->url(1) }}">{{__("First")}}</a></li>
                                <li class="p-1"><a href="{{ $randomProfiles->previousPageUrl() }}">{{__("Previous")}}</a></li>
                            @endif

                            @for ($i = max(1, $randomProfiles->currentPage() - 2); $i <= min($randomProfiles->lastPage(), $randomProfiles->currentPage() + 2); $i++)
                                <li class="p-1 {{ ($randomProfiles->currentPage() == $i) ? 'active' : '' }}">
                                    <a href="{{ $randomProfiles->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($randomProfiles->currentPage() < $randomProfiles->lastPage())
                                <li class="p-1"><a href="{{ $randomProfiles->nextPageUrl() }}">{{__("Next")}}</a></li>
                                <li class="p-1"><a href="{{ $randomProfiles->url($randomProfiles->lastPage()) }}">{{__("Last")}}</a></li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <livewire:dating-template-profile1.templates.categories-and-province :idSite="$site_data->id"/>
    @if(!empty($adsPageData->content_buttom))
        <section class="section section--content">
            <div class="container">
                <div id="footerText">  
                    {!! $adsPageData->content_buttom !!}
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
    <livewire:dating-template-profile1.templates.footer :siteId="$site_data->id" :domain="$domain"/>
</div>
@push("scripts")
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
