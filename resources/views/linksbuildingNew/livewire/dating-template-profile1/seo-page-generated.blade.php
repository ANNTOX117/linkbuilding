@push("styles")
    <link rel="stylesheet" href="{{'/linksbuildingNew/css/templates/datingTemplateProfile1/templates/region.css'}}">
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
    <section class="section section--seo">
        <div class="container my-5">
            <div class="text-uppercase my-2">
                <h2>{{$seoPage->title}}</h2>
            </div>
            <div class="seoPage__description paragraph paragraph--small my-5">
                {!!$seoPage->description_top!!}
            </div>
            <section class="section section--cities">
                <div class="container">
                    <div class="city">
                        <div class="grid">
                            @foreach ($profilesByCity as $profile)
                            <div class="grid-item">
                                <div class="block">
                                    <figure>
                                        <a href="{{route('interior-profile',$profile)}}">
                                            <img src="{{$profile->image}}" alt="" loading="lazy">
                                        </a>
                                    </figure>
                                    <div class="content">
                                        <h3 class="title title--block">
                                            <a href="{{route('interior-profile',$profile)}}">
                                                {{$profile->title}}
                                            </a>
                                        </h3>
                                        <p class="paragraph--small">
                                           {{substring_text(strip_tags($profile->description),50)}}... 
                                        </p>
                                        <p class="paragraph paragraph--small">
                                            <a href="{{$profile->path}}">{{$profile->name}}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center">
                            {{$profilesByCity->links()}}
                        </div>
                    </div>
                </div>
            </section>
            <div class="seoPage__description paragraph paragraph--small my-2">
                {!!$seoPage->description_buttom!!}
            </div>
        </div>
    </section>
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
    <livewire:dating-template-profile1.templates.categories-and-province :idSite="$site_data->id" :dinamic="true" :seoPage="$seoPage" />
    <livewire:dating-template-profile1.templates.register-footer :siteId="$site_data->id"/>
    <livewire:dating-template-profile1.templates.looking-for-contacts :idSite="$site_data->id"/>
    <livewire:dating-template-profile1.templates.footer :domain="$domain" :siteId="$site_data->id"/>
</div>
@push('scripts')
    <script>
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