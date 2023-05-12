@push("styles")
    {{-- <link rel="stylesheet" href="{{'/linksbuildingNew/css/templates/datingTemplateProfile1/templates/home.css'}}"> --}}
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
    <section class="section section--profiles">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="content">
                        @if(!empty($homePageData->content_top))
                            <div id="headerText">
                                <h1>{{$homePageData->title}}</h1>
                                {!! $homePageData->content_top !!}
                            </div>
                        @endif
                        <button id="open-filters" class="btn btn-primary d-lg-none ms-auto d-block mb-4 open-nav">
                            <i class="fas fa-filter"></i>
                        </button>
                        <div class="profiles">
                            <div class="grid">
                                @if(!empty($randomProfiles))
                                    @foreach($randomProfiles as $profile)
                                        <div class="grid-item">
                                            <div class="profile">
                                                <figure class="profile__figure" >
                                                    <a href="{{route("interior-profile",$profile)}}">
                                                        <img src="{{$profile->image}}" alt="" class="profile__figure__image" loading="lazy">
                                                    </a>
                                                </figure>
                                                <div class="profile__content px-3">
                                                    <h3 class="title title--block">
                                                        <a href="{{route("interior-profile",$profile)}}">
                                                            {!!__($profile->title)!!}
                                                        </a>
                                                    </h3>
                                                    <p class="profile__content__paragraph">
                                                        {!!__(implode(' ', array_slice(explode(' ', strip_tags($profile->description)), 0, 20))).' ... '!!}
                                                    </p>
                                                    <p class="paragraph paragraph__small--city c-gray mt-4">
                                                        {{__("City")}}: <a href="{{route('find-date',["city"=>$profile->cityPath])}}">{{$profile->cityName}}</a> 
                                                        <span class="d-block">{{__("Province")}}: <a href="{{route("regions.name",["name"=>$profile->provincieUrl])}}">{{$profile->provincieName}}</a></span>
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
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <livewire:dating-template-profile1.templates.aside-contact-category-region :idSite="$site_data->id"/>
                </div>
            </div>
        </div>
    </section>
    <livewire:dating-template-profile1.templates.categories-and-province :idSite="$site_data->id"/>
    @if(!empty($homePageData->content_buttom))
        <section class="section section--content">
            <div class="container">
                <div id="footerText">      
                    {!! $homePageData->content_buttom !!}
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
@push('scripts')
<script>
    $( window ).on( "load", function() {
        $(".profiles").addClass("loaded");
        $("#headerText h1").addClass("title title--section");
        $("#headerText h2").addClass("title title--section");
        $("#headerText h3").addClass("title title--section");
        $("#headerText p").addClass("paragraph c-gray");
        $("#footerText h2").addClass("title title--section");
        $("#footerText h3").addClass("title title--section");
        $("#footerText p").addClass("paragraph c-gray");
    });
</script>
@endpush