<div class="container-fluid">
    @php
        $domain  = domain();
        $category = subdomain();
    @endphp
    <div class="row">
    {{-- @if(empty(session('category'))) --}}
    @if(empty($category))
        {{-- @if(!empty($id_filter) && count($posts) > 0)
            <div class="col-md-10 col-sm-12 my-3 mx-auto text-center">{!! $posts[0]->headerText !!}</div> --}}
        @if(!empty($meta_info))
            <div class="col-md-10 col-sm-12 my-3 mx-auto text-center">{!! $meta_info->header !!}</div>
        @else
            @if(!empty($site->blog_header))
                <div class="col-md-10 col-sm-12 my-3 mx-auto text-center">{!! $site->blog_header !!}</div>
            @endif
        @endif
    @else
        @if(!empty($site->daughter_blog_header))
            <div class="col-md-10 col-sm-12 my-3 mx-auto text-center">{!! $site->daughter_blog_header !!}</div>
        @endif
    @endif
    {{-- style="display: flex; flex-wrap:no-wrap; justify-content: space-between" --}}
    <div class="col-md-10 col-sm-12 mx-auto">
        {{-- <div class="col-10"> --}}
            <div class="card static-text my-2">
                <div class="card-header text-center py-3">
                    <h1 class="section--title">{{ $title }}</h1>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        @if(!empty($posts[0]))
                            @foreach($posts as $post)
                                <div class="col-12 col-lg-6">
                                    <div class="card card-post mb-4">
                                        <a href="{{ route('post_' . App::getLocale(), ['category' => $post->category_name,'url' => $post->url]) }}"><div class="post-image" style="background-image: url('{{ get_image($post->image) }}');"></div></a>
                                        <div class="card-body">
                                            <a href="{{ route('post_' . App::getLocale(), ['category' => $post->category_name, 'url' => $post->url]) }}"><h2 class="card-title">{{ $post->title }}</h2></a>
                                            <span class="post-category">{{__('Category')}}: <a class="post-category post-category-a" href="{{ route('blog_' . App::getLocale(),['category' => $post->category_name, 'id' => $post->category]) }}">{{ $post->categories->name }}</a></span>
                                            <p class="card-text">{{ get_excerpt($post->description) }}</p>
                                            <a href="{{ route('post_' . App::getLocale(), ['category' => $post->category_name, 'url' => $post->url]) }}" class="btn btn-primary color-box adjust-text">{{__('Read more')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-12">
                                {{ $posts->links() }}
                            </div>
                        @else
                            <div class="col-12 text-center">
                                <em class="text-muted my-5">{{__('No post added yet')}}</em>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="jumbotron" style="background-color: white; color:black; border: 1px solid rgb(221, 217, 217);">
            <h1 class="display-4"><b>Categories</b></h1>
            <hr class="my-4" style="border-top: 1px solid black;">
            @foreach ($blogCategories as $cat)
                <a href="{{ route('blog_' . App::getLocale()).'/'.$cat->id }}" data-id="{{ $cat->id }}"><p style="color: black;">{{$cat->name}}</p></a>
            @endforeach
        </div> --}}
        {{-- <div class="card-body p-5">
            <div>
                <h2>Categories</h2>
                @foreach ($blogCategories as $cat)
                    <a href="{{ route('blog_' . App::getLocale()).'/'.$cat->id }}" data-id="{{ $cat->id }}"><p>{{$cat->name}}</p></a>
                @endforeach
            </div>
        </div> --}}
    </div>
    {{-- @if(empty(session('category'))) --}}
    @if(empty($category))
        {{-- @if(!empty($id_filter) && count($posts) > 0)
            <div class="col-md-10 col-sm-12 my-3 mx-auto text-center">{!! $posts[0]->footerText !!}</div> --}}
        @if(!empty($meta_info))
            <div class="col-md-10 col-sm-12 my-3 mx-auto text-center">{!! $meta_info->footer !!}</div>
        @else
            @if(!empty($site->blog_footer))
                <div class="col-md-10 col-sm-12 my-3 mx-auto text-center">{!! $site->blog_footer !!}</div>
            @endif
        @endif
    @else
        @if(!empty($site->daughter_blog_footer))
            <div class="col-md-10 col-sm-12 my-3 mx-auto text-center">{!! $site->daughter_blog_footer !!}</div>
        @endif
    @endif
</div>
</div>