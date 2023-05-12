@push("styles")
    {{-- <link rel="stylesheet" href="{{'/linksbuildingNew/css/templates/datingTemplateProfile1/templates/region.css'}}"> --}}
@endpush
<div>
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
    <section class="section section--cities m-3">
        <div class="container">
            <h3 class="title title--section" data-aos="fade-up">
                <i class="fas fa-search"></i> {{__("Find your new date")}}
            </h3>
            @if(count($profiles)>0)
            <div class="city">
                <div class="grid">
                    @foreach ($profiles as $profile)
                    <div class="grid-item">
                        <div class="block">
                            <figure>
                                <a href="{{route('interior-profile',$profile->url)}}">
                                    <img src="{{$profile->image}}" alt="" loading="lazy">
                                </a>
                            </figure>
                            <div class="content">
                                <h3 class="title title--block">
                                    <a href="{{route('interior-profile',$profile->url)}}">
                                        {!!$profile->title!!}
                                    </a>
                                </h3>
                                <p class="paragraph paragraph--small c-gray">
                                    {!!__(strip_tags($profile->description))!!}
                                </p>
                                <p class="paragraph paragraph--small c-gray">
                                    <a href="{{route("find-date",["city"=>$profile->path])}}">{{$profile->cityName}}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            {{-- <div class="d-flex justify-content-center">
                {{$profiles->links()}}
            </div> --}}
            <div class="banner__large">
                <div class="d-flex justify-content-center">
                    {{$profiles->links()}}
                </div>
            </div>
            <div class="banner__compact">
                @if ($profiles->lastPage() > 1)
                    <ul class="pagination justify-content-center">
                        @if ($profiles->currentPage() > 1)
                            <li class="p-1"><a href="{{ $profiles->url(1) }}">{{__("First")}}</a></li>
                            <li class="p-1"><a href="{{ $profiles->previousPageUrl() }}">{{__("Previous")}}</a></li>
                        @endif

                        @for ($i = max(1, $profiles->currentPage() - 2); $i <= min($profiles->lastPage(), $profiles->currentPage() + 2); $i++)
                            <li class="p-1 {{ ($profiles->currentPage() == $i) ? 'active' : '' }}">
                                <a href="{{ $profiles->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($profiles->currentPage() < $profiles->lastPage())
                            <li class="p-1"><a href="{{ $profiles->nextPageUrl() }}">{{__("Next")}}</a></li>
                            <li class="p-1"><a href="{{ $profiles->url($profiles->lastPage()) }}">{{__("Last")}}</a></li>
                        @endif
                    </ul>
                @endif
            </div>
            @else
            <div class="d-flex justify-content-center">
                <p>{{__("No users found")}}</p>
            </div>
            @endif
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
    <livewire:dating-template-profile1.templates.register-footer/ :siteId="$site_data->id">
    <livewire:dating-template-profile1.templates.looking-for-contacts :idSite="$site_data->id"/>
    <livewire:dating-template-profile1.templates.footer :domain="$domain" :siteId="$site_data->id"/>
</div>
@push("scripts")
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
