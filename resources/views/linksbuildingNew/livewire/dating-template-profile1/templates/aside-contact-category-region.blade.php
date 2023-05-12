<div class="nabar-right">
    <button id="open-filters" class="btn btn-primary d-lg-none ms-auto d-block mt-3 me-3">
        <i class="fas fa-times"></i>
    </button>
    <div class="widget">
        <div class="widget__header">
            <h3 class="title title--widget">
                {{__("Sex Contact Search")}}
            </h3>
        </div>
        <form method="POST" action="{{route('search')}}">
            @csrf
            <label>
                {{__("I'm a:")}}
            </label>
            <select class="form-control" wire:model="iAm">
                <option value="Man">{{__("Man")}}</option>
                <option value="Vrouw">{{__("Woman")}}</option>
                <option value="Stel">{{__("Couple")}}</option>
            </select>
            <label>
                {{__("I'm looking for a")}}
            </label>
            <select class="form-control" wire:model="sex" name="sex">
                <option value="Vrouw">{{__("Woman")}}</option>
                <option value="Man">{{__("Man")}}</option>
                <option value="Stel">{{__("Couple")}}</option>
            </select>
            @error('sex') <small class="text-danger p-2">{{ __("$message") }}</small> @enderror
            <label>
                {{__("Regio's:")}}
            </label>
            <select class="form-control" wire:model="province" name="province">
            @if(!empty($allProvinces))
                @foreach ($allProvinces as $province)
                    <option value="{{$province->name}}">{{$province->name}}</option>
                @endforeach
            @endif
            </select>
            @error('province') <small class="text-danger p-2">{{ __("$message") }}</small> @enderror
            <button class="btn btn-blue" type="submit">{{__("Search")}}</button>
        </form>
    </div>
    @if (isset($banners) && count($banners)>0)
        @php
            $banner1 = $this->getNextElement($banners);
            $banner2 = $this->getNextElement($banners);
        @endphp
        <div class="banner text-center">
            <a href="{{$banner1["url_redirect"]}}" target="_blank"><img src="{{$banner1["url_file"]}}" alt=""></a>
        </div>
    @endif
    <div class="widget">
        <div class="widget__header">
            <h3 class="title title--widget">
                {{__("Popular Categories")}}
            </h3>
        </div>
        <p class="widget__paragraph">
            {{__("Zoekt u iets speciaals?")}}
        </p>
        <ul class="categories">
        @if (!empty($categories))
            @foreach ($categories as $category)
            <li>
                <a href="{{route('category',Str::slug($category->name))}}">
                    {{$category->name}}
                </a>
            </li>    
            @endforeach
        @endif
        </ul>
        <a href="{{route('all-categories')}}" class="btn btn-blue">
            {{__("All Categories")}}
        </a>
    </div>
    @if (isset($banners) && count($banners)>0 && isset($banner2))
        <div class="banner text-center">
            <a href="{{$banner2["url_redirect"]}}" target="_blank"><img src="{{$banner2["url_file"]}}" alt=""></a>
        </div>
    @endif
    {{-- <div class="widget">
        <div class="widget__header">
            <h3 class="title title--widget">
                {{__("Popular Regions")}}
            </h3>
        </div>
        <p class="widget__paragraph">
            {{__("Sex contacts in your region.")}}<br>{{__("Just like you, many horny people are looking for sex!")}}
        </p>
        <ul class="regios">
            @if (!empty($regions))
            @foreach ($regions as $region)
            <li>
                <a href="{{route('regions')}}#{{Str::slug($region->name)}}">
                    {{$region->name}}
                </a>
            </li>    
            @endforeach
        @endif
        </ul>
        <a href="{{route('regions')}}" class="btn btn-blue">
            {{__("All Regios")}}
        </a>
    </div> --}}
</div>