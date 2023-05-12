<div class="inline-block">
    <a href="javascript:void(0)" wire:click="purchase('{{ $item }}', {{ $identifier }}, '{{ $price }}')" wire:loading.remove class="btn btn-primary {{ $styles }}">
        <i class="fas fa-shopping-cart"></i> {{__('Add to cart')}}
    </a>
    <div wire:loading wire:target="purchase">
        <a href="javascript:void(0)" class="btn btn-primary btn-loading {{ $styles }}">
            <i class="fas fa-spinner fa-spin"></i> {{__('Loading')}}...
        </a>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('doConfirm', event => {
            dialogConfirm(event.detail.message, event.detail.confirm, event.detail.cancel, event.detail.redirect);
        });
    </script>
@endpush
