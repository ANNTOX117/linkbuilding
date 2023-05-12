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
    <section class="section section--interior-blog">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="content">
                        <figure>
                            <a href="#">
                                <img src="{{$blog_by_url->image}}" alt="" loading="lazy">
                            </a>
                        </figure>
                        <div class="information">
                            <p class="paragraph small">{{__($blog_by_url->created_at->toFormattedDateString())}}</p>
                            <h3 class="title title--section">{{__($blog_by_url->title)}}</h3>
                            <p class="paragraph small">
                                <strong><i class="far fa-calendar"></i> {{__("Date:")}}</strong> {{__($blog_by_url->created_at->toFormattedDateString())}} 
                                <strong><i class="far fa-calendar"></i> {{__("Category:")}}</strong> <a href="{{route("category",$blog_by_url->url)}}">{{__($blog_by_url->name)}}</a>
                            </p>
                        </div>
                        <div class="description">
                            <div class="paragraph">
                                {!!$blog_by_url->description!!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <livewire:dating-template-profile1.templates.related-links :idSite="$site_data->id"/>
                </div>
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
    <livewire:dating-template-profile1.templates.looking-for-contacts :idSite="$site_data->id"/>
    <livewire:dating-template-profile1.templates.footer :domain="$domain" :siteId="$site_data->id"/>
</div>
@push("scripts")
<script>
    $("#daughter_blog_header h1").addClass("title title--section");
    $("#daughter_blog_header h2").addClass("title title--section");
    $("#daughter_blog_header h3").addClass("title title--section");
    $("#daughter_blog_header p").addClass("paragraph c-gray");
    $("#daughter_blog_footer h1").addClass("title title--section");
    $("#daughter_blog_footer h2").addClass("title title--section");
    $("#daughter_blog_footer h3").addClass("title title--section");
    $("#daughter_blog_footer p").addClass("paragraph c-gray");
</script>
@endpush