<div class="col-10 cheight">
    <div class="card static-text my-2">
        <div class="card-header text-center py-3">
            <h1>{{ $title }}</h1>
        </div>
        <div class="card-body">
            <div class="row">
                @if(!empty($posts[0]))
                    @foreach($posts as $post)
                        <div class="col-6">
                            <div class="card card-post mb-4">
                                <a href="{{ route('post_' . App::getLocale(), ['url' => $post->url]) }}"><div class="post-image" style="background-image: url('{{ get_image($post->image) }}');"></div></a>
                                <div class="card-body">
                                    <a href="{{ route('post_' . App::getLocale(), ['url' => $post->url]) }}"><h2 class="card-title">{{ $post->title }}</h2></a>
                                    <p class="card-text">{{ get_excerpt($post->description) }}</p>
                                    <a href="{{ route('post_' . App::getLocale(), ['url' => $post->url]) }}" class="btn btn-primary color-box adjust-text">{{__('Read more')}}</a>
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
