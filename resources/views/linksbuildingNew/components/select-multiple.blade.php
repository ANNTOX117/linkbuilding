<div
    wire:ignore
    x-data="{ values: @entangle($attributes->wire('model')), choices: null }"
    x-init="
        choices = new Choices($refs.multiple, {
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
        });

        for (const [value, label] of Object.entries(values)) {
            choices.setChoiceByValue(value || label)
        }

        $refs.multiple.addEventListener('change', function (event) {
            values = []
            Array.from($refs.multiple.options).forEach(function (option) {
                values.push(option.value || option.text)
            })
        })
    "
>

    <select x-ref="multiple" multiple="multiple">
        @if(!empty($options))
            @foreach($options as $key => $option)
                <option value="{{ $key }}" @if(!empty($selection) and in_array($key, $selection)) selected="selected" @endif>{{ $option }}</option>
            @endforeach
        @endif
    </select>
</div>
