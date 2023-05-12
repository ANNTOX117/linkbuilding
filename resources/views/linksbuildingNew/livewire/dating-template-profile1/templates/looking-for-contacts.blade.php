<section class="section section--profiles-related">
    <div class="container">
        <h3 class="title title--section">
            {{__("Newest members looking for a sex contact:")}}
        </h3>
    </div>
    <div class="container-fluid">
        <div class="owl-carousel owl-carousel-profiles">
            @if (!empty($randomProfiles))
                @foreach ($randomProfiles as $profile)
                <div class="profile">
                    <figure class="profile__figure">
                        <a href="{{route('interior-profile',$profile)}}">
                            <img class="profile__figure__image" src="{{$profile->image}}" alt="{{$profile->title}}" loading="lazy">
                        </a>
                    </figure>
                    <div class="profile__content text-center">
                        <h3 class="title title--widget">
                            <a href="{{route('interior-profile',$profile)}}">
                                {{$profile->title}}
                            </a>
                        </h3>
                        <p class="paragraph paragraph--small">
                            <a href="{{route("find-date",["city"=>$profile->cityPath])}}">{{$profile->cityName}}</a>
                        </p>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</section>