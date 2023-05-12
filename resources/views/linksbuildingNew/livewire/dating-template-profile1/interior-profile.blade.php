@push("styles")
    {{-- <link rel="stylesheet" href="{{'/linksbuildingNew/css/templates/datingTemplateProfile1/templates/interior-profile.css'}}"> --}}
@endpush
<div>
    <livewire:dating-template-profile1.templates.breadcrumb />
    @if(isset($bannersPC) && count($bannersPC)>0)
        <div class="mt-4">
            @php
                $pc = $this->getNextElementPC($bannersPC);
            @endphp
            <div class="banner text-center banner__large">
                <a href="{{$pc["url_redirect"]}}" target="_blank"><img src="{{$pc["url_file"]}}" alt="" loading="lazy"></a>
            </div>
        </div>
    @endif
    @if(isset($bannersMovile) && count($bannersMovile)>0)
        <div class="mt-4">
            @php
                $movile = $this->getNextElementMovile($bannersMovile);
            @endphp
            <div class="banner text-center banner__compact">
                <a href="{{$movile["url_redirect"]}}" target="_blank"><img src="{{$movile["url_file"]}}" alt="" loading="lazy"></a>
            </div>
        </div>
    @endif
    <section class="section section--interior-profile">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <button id="open-filters" class="btn btn-primary d-lg-none ms-auto d-block mb-4 open-nav">
                        <i class="fas fa-filter"></i>
                    </button>
                    <div class="profile">
                        <h3 class="title title--small c-white">
                            "{{__(substr(array_column($atributesByProfile, null, 'name')['about_me']["value"],0,100)).'...'}}"
                        </h3>
                        <div class="row">
                            <div class="col-md-4">
                                <figure>
                                    <img src="{{$profile_by_url->image}}" alt="" loading="lazy">
                                </figure>
                            </div>
                            <div class="col-md-8">
                                <ul class="details">
                                    <li>
                                        {{__("My name is")}}: <strong>{{array_column($atributesByProfile, null, 'name')['name']["value"]}}</strong>
                                    </li>
                                    <li>
                                        {{__("I'm")}}: <strong>{{array_column($atributesByProfile, null, 'name')['gender']["value"]}}</strong>
                                    </li>
                                    <li>
                                        {{__("I live in")}}: <strong>{{array_column($atributesByProfile, null, 'name')['city']["value"]}}</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="description">
                        <h3 class="title title--main">
                            <i class="fas fa-edit"></i> {{array_column($atributesByProfile, null, 'name')['name']["value"]}} {{__("writes")}}:
                        </h3>
                        <p class="paragraph">
                            {{array_column($atributesByProfile, null, 'name')['about_me']["value"]}}
                        </p>
                    </div>
                    <div class="contact-now">
                        <h3 class="title title--section">
                            {{__("Do you want sex with")}} {{array_column($atributesByProfile, null, 'name')['name']["value"]}}?
                        </h3>
                        <p class="paragraph paragraph--big">
                            {{__("Then click on this link now")}}!
                        </p>
                    
                        <a wire:click="sendMessageModal" class="btn btn-primary">
                            <i class="fas fa-comments"></i>  {{__("Contact")}} {{array_column($atributesByProfile, null, 'name')['name']["value"]}} {{__("now")}}
                        </a>
                    </div>
                    <h3 class="my-4 title title--section">{{__("Reviews van onze gebruikers")}}</h3>
                    @if (count($reviews)>0)
                        <div class="owl-carousel owl-theme" id="carousel_reviews">
                            @foreach ($reviews as $review)
                                <div class="item border rounded p-2">
                                    <div class="d-flex">
                                        <span class="rounded-circle p-2 bg-light m-2"><i class="fas fa-user"></i></span><p class="align-self-end"><strong>{{$review->writted_by}}</strong></p>
                                    </div>
                                    <p><i>"{{__($review->comment)}}"</i></p>
                                    <p>{{__("Rating")}}: <span style="color: #ff3066">{!!str_repeat('<i class="fas fa-star"></i>', $review->stars)!!}</span></p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center mt-3">
                            <h3>{{__("There is not reviews yet")}}!</h3>
                        </div>
                    @endif
                </div>
                <div class="col-lg-3">
                    <livewire:dating-template-profile1.templates.aside-contact-category-region :idSite="$site_data->id"/>
                </div>
            </div>
        </div>
    </section>
    <div wire:ignore.self class="modal fade" id="sendMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Send Mesagge To ').array_column($atributesByProfile, null, 'name')['name']["value"]}}</h4>
                </div>
                <div class="m-3">
                    <div class="form-group">
                        <label for="emailToRegister" class="form-label">{{__('Your email')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                        <input wire:model="emailToRegister" type="email" class="form-control" id="emailToRegister" placeholder="{{__('Write your email')}}"/>
                        @error('emailToRegister') <span class="error text-danger">{{__($message) }}</span> @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label for="message" class="form-label">{{__('Write your message')}} <sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup></label>
                        <textarea wire:model="msgToProfile" class="form-control" :errors="$errors"></textarea>
                        @error('msgToProfile') <span class="error text-danger">{{__($message) }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal" onclick="closeModal()">{{__('Close')}}</button>
                    <button type="button" class="btn btn-danger close-modal" data-dismiss="modal" wire:click="sendMessageToProfile">{{__('Send message')}}</button>
                </div>
            </div>
        </div>
    </div>
    @if(isset($bannersPC) && count($bannersPC)>0)
        <div class="mb-4">
            @php
                $pc = $this->getNextElementPC($bannersPC);
            @endphp
            <div class="banner text-center banner__large">
                <a href="{{$pc["url_redirect"]}}" target="_blank"><img src="{{$pc["url_file"]}}" alt="" loading="lazy"></a>
            </div>
        </div>
    @endif
    @if(isset($bannersMovile) && count($bannersMovile)>0)
        <div class="mb-4">
            @php
                $movile = $this->getNextElementMovile($bannersMovile);
            @endphp
            <div class="banner text-center banner__compact">
                <a href="{{$movile["url_redirect"]}}" target="_blank"><img src="{{$movile["url_file"]}}" alt="" loading="lazy"></a>
            </div>
        </div>
    @endif
    <livewire:dating-template-profile1.templates.register-footer :siteId="$site_data->id"/>
    <livewire:dating-template-profile1.templates.looking-for-contacts :idSite="$site_data->id"/>
    <livewire:dating-template-profile1.templates.footer :domain="$domain" :siteId="$site_data->id"/>
</div>
@push("scripts")
<script>
    window.addEventListener('sendMessage', event => {
        $('#sendMessage').modal('show');
    });
    function closeModal() {
        $('#sendMessage').modal('hide');
    }
    $(document).ready(function(){
        if (document.getElementById("carousel_reviews")) {
            $('.owl-carousel').owlCarousel({
                loop:true,
                margin:20,
                nav:true,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:2
                    },
                    1000:{
                        items:3
                    }
                }
        });
        let nav_slider = document.querySelector("div.owl-nav");
        nav_slider.style.textAlign="center";
        nav_slider.style.fontSize = "4em";
        }
    });
</script>
@endpush