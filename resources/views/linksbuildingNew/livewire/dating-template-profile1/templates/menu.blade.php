@push("styles")
    <link rel="stylesheet" href="{{'/linksbuildingNew/css/templates/datingTemplateProfile1/templates/home.css'}}">
@endpush

<header class="header" id="mainHeader">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                @if (!empty($site->logo))
                    <img src="{{$site->logo}}" alt="Logo" loading="lazy">
                @else
                    {{ $name??false }}
                @endif
            </a>
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMainHeader" aria-controls="navbarMainHeader" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarMainHeader">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{request()->segment(count(request()->segments())) === null ?"active":false}}" href="{{route("home")}}"><i class="fas fa-home"></i> {{__("Home")}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->segment(count(request()->segments())) === "ads"?"active":false}}" href="{{route("ads")}}"><i class="fas fa-heart"></i> {{__("All Ads")}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->segment(count(request()->segments())) === "regions"?"active":false}}" href="{{route("regions")}}"><i class="fas fa-map-marker-alt"></i> {{__("Regions")}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->segment(count(request()->segments())) === "blogs"?"active":false}}" href="{{route('blog')}}"><i class="fas fa-lightbulb"></i> {{__("Blog")}}</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{request()->segment(2) === "search"?"active":false}}" href="#"><i class="fas fa-search"></i> {{__("To search")}}</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link {{request()->segment(count(request()->segments())) === "categories"||request()->segment(count(request()->segments())) ==="categorieen"?"active":false}}" href="{{route("all-categories")}}"><i class="fas fa-tag"></i> {{__("Categories")}}</a>
                    </li>
                </ul>
                <form class="d-flex header__search" wire:submit.prevent="seachUser">
                    <input class="form-control" type="search" placeholder="{{__("Search")}}..." aria-label="Search" wire:model="nameToSeach">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </nav>
</header>