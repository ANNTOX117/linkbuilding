<div class="offset-md-1 col-md-10 col-sm-12">
    <div class="post-image" style="background-image: url('{{ get_image($post->image) }}');"></div>
    <div class="card static-text mb-2">
        <div class="card-body border py-5">
            <h1 class="mb-4">{{ $title }}</h1>
            <span class="post-category">{{__('Category')}}: <a class="post-category post-category-a" href="{{ route('blog_' . App::getLocale(), ['category' => $post->categories->name, 'id' => $post->category ]) }}">{{ $post->categories->name }}</a></span>
            {!! $post->description !!}
            <a href="{{ route('blogs_' . App::getLocale()) }}" class="btn btn-primary color-box adjust-text mt-5">{{__('Back')}}</a>
        </div>
    </div>
</div>
