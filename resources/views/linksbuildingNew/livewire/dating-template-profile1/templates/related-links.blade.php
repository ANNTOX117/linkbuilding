<article>
    <div class="nabar-right">
        <h3 class="title title--small">
            {{__("Related links")}}
        </h3>
        <div class="widget">
            <div class="widget__header">
                <h3 class="title title--widget">
                    {{__("More Sex Ads")}}
                </h3>
            </div>
            <p class="widget__paragraph">
                {{__("Many people are looking for sex without relationship hassle:")}}
            </p>
        </div>
    </div>
    @php
        $banner1 = $this->getNextElement($banners);
    @endphp
    @if($banner1)
        <div class="banner text-center">
            <a href="{{$banner1["url_redirect"]}}" target="_blank"><img src="{{$banner1["url_file"]}}" alt=""></a>
        </div>
    @endif
    <div class="navbar-related-profiles">
        @foreach ($randomProfiles as $profile)
        <div class="profile">
            <figure class="profile__figure">
                <a href="{{route('interior-profile',$profile)}}">
                    <img class="profile__figure__image" src="{{$profile->image}}" alt="" loading="lazy">
                </a>
            </figure>
            <div class="profile__content">
                <h3 class="title title--widget">
                    <a href="{{route('interior-profile',$profile)}}">
                        {{$profile->title}}
                    </a>
                </h3>
                <a href="{{route('interior-profile',$profile)}}" class="btn btn-primary">
                    {{__("View Profile")}}
                </a>
            </div>
        </div>
        @endforeach
        {{-- <div class=" btn-bottom text-center">
            <a href="{{route("ads")}}" class="btn btn-primary">
                {{__("All ads!!")}}
            </a>
        </div> --}}
    </div>
</article>