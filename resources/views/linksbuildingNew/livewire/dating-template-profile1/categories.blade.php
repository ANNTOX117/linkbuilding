@push("styles")
    {{-- <link rel="stylesheet" href="{{'/linksbuildingNew/css/templates/datingTemplateProfile1/templates/category.css'}}"> --}}
@endpush
<div>
    <livewire:dating-template-profile1.templates.breadcrumb />
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
    <section class="section section--category">
        <div class="container">
            {{-- <h3 class="title title--section" data-aos="fade-up">
                <i class="fas fa-tag"></i> {{__("Sex contacts by category")}}
            </h3>
            <p class="paragraph" data-aos="fade-up">
                {{__("Just like you, many horny people are looking for sex!")}}
            </p> --}}
            <div id="daughter_header">
                @if(!empty($categoriesMetaData->content_top))
                    <h1>{!!__($categoriesMetaData->title)!!}</h1>
                    {!! __($categoriesMetaData->content_top) !!}
                @endif
            </div>
            <div class="category">
                <h3 class="title title--section" data-aos="fade-up">
                    <i class="fas fa-tag"></i> {{__("Select the category")}}
                </h3>
                <div class="row">
                    @if (!empty($categoriesBySite))
                        @foreach ($categoriesBySite as $category)
                        <div class="col-lg-4 col-md-6">
                            <a href="{{route("category",$category)}}" data-aos="fade-up">
                                {{__($category->name)}}
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
    @if(!empty($categoriesMetaData->content_buttom))
        <section class="section section--content">
            <div class="container">
                <div id="daughter_footer">  
                    {!! $categoriesMetaData->content_buttom !!}
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
        $("#daughter_header h1").addClass("title title--section");
        $("#daughter_header h2").addClass("title title--section");
        $("#daughter_header h3").addClass("title title--section");
        $("#daughter_header p").addClass("paragraph c-gray");
        $("#daughter_footer h1").addClass("title title--section");
        $("#daughter_footer h2").addClass("title title--section");
        $("#daughter_footer h3").addClass("title title--section");
        $("#daughter_footer p").addClass("paragraph c-gray");
    </script>
@endpush