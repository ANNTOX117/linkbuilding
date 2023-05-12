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
    <section class="section section--regions">
        <div class="container">
            @if(!empty($regionsPageData->content_top))
                <div id="headerText">
                    <h1>{{$regionsPageData->title}}</h1>
                    {!! $regionsPageData->content_top !!}
                </div>
            @endif
            <div class="regions">
                <h3 class="title title--section" data-aos="fade-up">
                    <i class="fas fa-map-marker-alt"></i> {{__("Select your region")}}
                </h3>
                <div class="row">
                    @if (!empty($profilesAmountByProvince))
                        @foreach ($profilesAmountByProvince as $province)
                        <div class="col-lg-4 col-md-6">
                            <a href="#{{Str::slug($province->value)}}" data-aos="fade-up">
                                {{$province->value}}
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="section section--cities">
        <div class="container">
            @if(!empty($provincesBySite))
                @foreach ($provincesBySite as $province)
                <div id="{{Str::slug($province->name)}}" class="city">
                    <h3 class="title title--section" data-aos="fade-up">
                        <i class="fas fa-map-marker-alt"></i> {{$province->name}}
                    </h3>
                    <p class="paragraph" data-aos="fade-up">
                        {{__("Sex Ads in")}} {{$province->name}}
                    </p>
                    <div class="grid">
                        @foreach ($this->getProfilesByProvince($province->id) as $profile)
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
                                        {{__(substring_text(strip_tags($profile->description),50))}}... 
                                     </p>
                                    <p class="paragraph paragraph--small c-gray">
                                        <a href="{{route("find-date",["city"=>$profile->path])}}">{{$profile->name}}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if (count($this->getProfilesByProvince($province->id))>0) 
                        <div class="text-center">
                            <a href="{{route('regions.name',["name"=>Str::slug($province->name)])}}" class="btn btn-primary">
                                {{__("VIEW ALL IN")}} {{strtoupper($province->name)}} <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </div>
                    @endif
                </div>        
                @endforeach
            @endif
        </div>
    </section>
    @if(!empty($regionsPageData->content_buttom))
        <section class="section section--content">
            <div class="container">
                <div id="footerText">
                    {!! $regionsPageData->content_buttom !!}
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
        $("#headerText h1").addClass("title title--section");
        $("#headerText h2").addClass("title title--section");
        $("#headerText h3").addClass("title title--section");
        $("#headerText p").addClass("paragraph c-gray");
        $("#footerText h1").addClass("title title--section");
        $("#footerText h2").addClass("title title--section");
        $("#footerText h3").addClass("title title--section");
        $("#footerText p").addClass("paragraph c-gray");
    </script>
@endpush