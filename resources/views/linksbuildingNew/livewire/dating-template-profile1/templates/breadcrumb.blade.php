<div class="container my-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" id="breadcrumb">
            @if(isset($path_and_route) && count($path_and_route)>0)
                @foreach ($path_and_route as $path)
                    @php
                        $url_check = basename(parse_url(url()->current(), PHP_URL_PATH)) === $path["path"];
                    @endphp
                    <li class='breadcrumb-item paragraph {{$url_check?"active":false}}' {{$url_check?'aria-current=page':false}} style="font-size: 16px">
                        <a href={{isset($path["params_route"])?route($path["route"],$path["params_route"]):route($path["route"])}}>{{__(ucfirst($path["path"]))}}</a>
                    </li>    
                @endforeach
            @endif
        </ol>
    </nav>
</div>
@push('scripts')
    <script>
        // Get the last li element within the ol element
        let lastLi = document.querySelector("#breadcrumb li:last-child");
        // Get the text content of the anchor tag
        let anchorTag = lastLi.querySelector("a");
        let text = anchorTag.textContent;
        // Remove the anchor tag from the li element
        anchorTag.parentNode.removeChild(anchorTag);
        // Add the text content to the li element
        lastLi.textContent = text;

    </script>
@endpush