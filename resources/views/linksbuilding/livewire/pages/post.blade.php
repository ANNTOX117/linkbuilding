<div class="col-10 cheight">
    <div class="post-image" style="background-image: url('{{ get_image($post->image) }}');"></div>
    <div class="card static-text mb-2">
        <div class="card-body border py-5">
            <h1 class="mb-4">{{ $title }}</h1>
            <span class="post-category">{{__('Category')}}: {{ $post->categories->name }}</span>
            {!! $post->description !!}
            <a href="{{ route('blog_' . App::getLocale()) }}" class="btn btn-primary color-box adjust-text mt-5">{{__('Back')}}</a>
        </div>
    </div>
</div>
