<div class="col-10 cheight">
    <div class="card static-text my-2">
        <div class="card-header text-center py-3">
            <h1>{{ $title }}</h1>
        </div>
        <div class="card-body border border-radius-top py-5">
            <p class="mb-4">{{__('Would you like to place a link on one of our daughter pages? Please contact us using the form below')}}.</p>
            <div class="alert alert-warning alert-sent alert-dismissible fade show" role="alert" style="display: none">
                {{__('Thank you, we have received your message, we will reply as soon as possible')}}
            </div>
            <form wire:submit.prevent="submit" class="form-contact">
                <div class="form-group">
                    <label for="name">{{__('Name')}}</label>
                    <input wire:model="name" type="text" class="form-control" id="name">
                    @error('name') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="email">{{__('Email')}}</label>
                    <input wire:model="email" type="email" class="form-control" id="email">
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>
                @if(empty(session('category')))
                <div class="form-group">
                    <label for="page">{{__('Pagina')}}</label>
                    <select wire:model="page" class="form-control custom-select" id="page">
                        <option value="">{{__('Select an option')}}</option>
                        <option value="{{__('Homepage')}}">{{__('Homepage')}}</option>
                        @if(!empty($daughters))
                            @foreach($daughters as $daughter)
                                <option value="{{ $daughter->id }}">{{ $daughter->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('page') <span class="error">{{ $message }}</span> @enderror
                </div>
                @endif
                <div class="form-group">
                    <label for="subject">{{__('Subject')}}</label>
                    <input wire:model="subject" type="text" class="form-control" id="subject">
                    @error('subject') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="message">{{__('Message')}}</label>
                    <textarea wire:model="message" class="form-control" id="message"></textarea>
                    @error('message') <span class="error">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-primary color-box adjust-text"><i class="far fa-paper-plane"></i> {{__('Send')}}</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('onSent', event => {
            $('.alert-sent').show();
        });
    </script>
@endpush
