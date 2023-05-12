<section class="section section--categories" style="background:url({{asset($url_image_categories_regions)}})no-repeat;background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="categories text-center">
                @if (isset($text_infront_left))
                    <h3 class="title title--section c-white">{{__($text_infront_left)}}</h3>    
                    @if (!empty($randomCities))
                    <ul>
                        @foreach ($randomCities as $city)
                        <li>
                            <a href="{{$city->path}}">
                                {{__($city->name)}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                @else
                    <h3 class="title title--section c-white">{{__("Categories")}}</h3>
                    @if (!empty($categories))
                    <ul>
                        @foreach ($categories as $category)
                        <li>
                            <a href="{{route('category',Str::slug($category->name))}}">
                                {{__($category->name)}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="places text-center">
                @if (isset($text_infront_right))
                    <h3 class="title title--section c-white">{{__($text_infront_right)}}</h3>
                    @if (!empty($nearlyCities))
                    <ul>
                        @foreach ($nearlyCities as $city)
                        <li>
                            <a href="{{$city->path}}">
                                {{$city->name}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                @else
                    <h3 class="title title--section c-white">{{__("Provincies")}}</h3>
                    @if (!empty($provinces))
                    <ul>
                        @foreach ($provinces as $province)
                        <li>
                            <a href="{{route('regions')}}#{{Str::slug($province->name)}}">
                                {{$province->name}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                @endif
                </div>
            </div>
        </div>
    </div>
</section>