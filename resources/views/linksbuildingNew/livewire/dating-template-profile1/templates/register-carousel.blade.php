<section class="section section--search" style="background: url('{{asset($url_image_carrusel)}}') no-repeat;background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="search text-center text-white">
                    @if (Request::is('/') && $content_top_register)
                        {!!__($content_top_register)!!}
                    @endif
                    <div class="form__register" data-aos="fade-up">
                        <input type="email" placeholder="{{__('Enter email address here...')}}" class="form-control" wire:model="email">
                        @error('email') <span class="error text-danger">{{__($message) }}</span> @enderror
                        <button class="btn btn-primary" wire:click="redirectToLandpage">
                            {{__("Sign up for free!")}} <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
