<section class="section section--subscribe">
    <div class="container text-white">
        <div class="row">
            <div class="col-lg-6">
                <div class="content">
                    <dd></dd>
                @if ($content_buttom_register)
                    {!!__($content_buttom_register)!!}
                {{-- @else
                    <h3 class="title title--block c-white">
                        {{__("Sign up now for unlimited access")}}
                    </h3>
                    <p class="paragraph paragraph--small c-white">
                        {{__("Register now for free at www.page.com and immediately take advantage of all the advantages. As a member you can not only view unlimited profiles, also sending and receiving messages is completely free. You only need an email address to get started.")}}
                    </p> --}}
                @endif
                </div>
            </div>
            <div class="col-lg-5 ms-auto">
                <div class="subscribe">
                    <h3 class="title title--block c-white">
                        {{__("Email:")}}
                    </h3>
                    <div>
                        <input type="email" placeholder="{{__("Enter your email below:")}}" class="form-control" wire:model='email'>
                        @error('email') <span class="error text-danger">{{__($message) }}</span> @enderror
                        <button class="btn btn-primary" wire:click="redirectToLandpage">
                            {{__("Sign up for free!")}} <i class="fas fa-long-arrow-alt-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>