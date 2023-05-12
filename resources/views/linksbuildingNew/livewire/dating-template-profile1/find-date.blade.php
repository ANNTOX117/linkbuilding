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
            @if (isset($findDateMetaData->content_top) && !empty($findDateMetaData->content_top))
                <div id="headerText">
                    {!!replace_text($findDateMetaData->content_top,$city->name)!!}
                </div>
            {{-- @else
                @if(!empty($site->headerText))
                    <div id="headerText">
                        {!! $site->headerText !!}
                    </div>
                @endif --}}
            @endif
            <div class="city">
                @if (isset($findDateMetaData->title) && !empty($findDateMetaData->title))
                    <h3 class="title title--section" data-aos="fade-up">
                        <i class="fas fa-map-marker-alt"></i> {{replace_text($findDateMetaData->title,$city->name)}}
                    </h3>
                @endif
                @if (count($allUsersByCity)>0)
                    <div class="grid">
                        @foreach ($allUsersByCity as $profile)
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
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center">
                        {{-- {{$allUsersByCity->links()}} --}}
                    </div>
                @else
                    <div class="text-center">{{__("No users found")}}</div>
                @endif
            </div>
        </div>
    </section>
    @if (isset($findDateMetaData->content_buttom) && !empty($findDateMetaData->content_buttom))
        <section class="section section--content">
            <div class="container">
                <div id="footerText">
                    {!! __(replace_text($findDateMetaData->content_buttom,$city->name)) !!}
                </div>
            </div>
        </section>
    {{-- @else
        @if(!empty($site->footerText))
            <section class="section section--content">
                <div class="container">
                    <div id="footerText">
                        {!! $site->footerText !!}
                    </div>
                </div>
            </section>
        @endif --}}
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