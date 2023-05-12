@section('title')
    {{ $title }}
@endsection

<div>
    <div class="content-wrapper center">
        @if(!empty($title))
            <div class="row page-title-header">
                <div class="col-12">
                    <div class="page-header">
                        <h4 class="page-title">{{ $title }}</h4>
                    </div>
                </div>
            </div>
        @endif
        <div class="cont ">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-rules">
                            <thead>
                            <tr>
                                <th>{{__('Option')}}</th>
                                <th>{{__('Value')}}</th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($settings as $setting)
                                    <tr>
                                        <td>{{__($setting->label)}}</td>
                                        <td>
                                            @if($setting->key == 'mollie_key')
                                                @if($edit == $setting->id)
                                                    <input wire:model="value" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('value') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $setting->value }}
                                                @endif
                                            @endif
                                            @if($setting->key == 'currency')
                                                @if($edit == $setting->id)
                                                    <select wire:model="value" class="form-control" id="select-currency" :errors="$errors" autocomplete="off">
                                                        <option value="€" @if($setting->option == '€') selected="selected" @endif>{{__('Euros')}}</option>
                                                        <option value="$" @if($setting->option == '$') selected="selected" @endif>{{__('Dollars')}}</option>
                                                        <option value="£" @if($setting->option == '£') selected="selected" @endif>{{__('Pounds')}}</option>
                                                    </select>
                                                    @error('value') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $setting->value }}
                                                @endif
                                            @endif
                                            @if($setting->key == 'price_article')
                                                @if($edit == $setting->id)
                                                    <input wire:model="value" type="number" class="form-control" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                                    @error('value') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $setting->value }}
                                                @endif
                                            @endif
                                            @if($setting->key == 'invoice_header')
                                                @if($edit == $setting->id)
                                                    <textarea wire:model="value" class="form-control" :errors="$errors" autocomplete="off"></textarea>
                                                    @error('value') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {!! nl2br($setting->value) !!}
                                                @endif
                                            @endif
                                            @if($setting->key == 'requested_articles')
                                                @if($edit == $setting->id)
                                                    <select wire:model="value" class="form-control" :errors="$errors">
                                                        <option value="">{{__('No')}}</option>
                                                        <option value="1">{{__('Yes')}}</option>
                                                    </select>
                                                    @error('value') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    @if(intval($setting->value) == 1) {{__('Yes')}} @else {{__('No')}} @endif
                                                @endif
                                            @endif
                                            @if($setting->key == 'register_coins')
                                                @if($edit == $setting->id)
                                                    <input wire:model="value" type="number" class="form-control" :errors="$errors" autocomplete="off" oninput="this.value=!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" />
                                                    @error('value') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $setting->value }}
                                                @endif
                                            @endif
                                            @if($setting->key == 'PAYPAL_SANDBOX_CLIENT_ID')
                                                @if($edit == $setting->id)
                                                    <input wire:model="value" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('value') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $setting->value }}
                                                @endif
                                            @endif
                                            @if($setting->key == 'PAYPAL_SANDBOX_CLIENT_SECRET')
                                                @if($edit == $setting->id)
                                                    <input wire:model="value" type="text" class="form-control" :errors="$errors" autocomplete="off" />
                                                    @error('value') <span class="error">{{ $message }}</span> @enderror
                                                @else
                                                    {{ $setting->value }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if(permission('general', 'update'))
                                                @if($edit == $setting->id)
                                                    <a class="blues" wire:click="save({{$setting->id}})" alt="{{__('Save')}}" title="{{__('Save')}}"><span class="block"><i class="far fa-save"></i></span></a>
                                                    <a class="reds" wire:click="cancel" alt="{{__('Cancel')}}" title="{{__('Cancel')}}"><span class="block"><i class="fas fa-times"></i></span></a>
                                                @else
                                                    <a class="blues" wire:click="edit({{$setting->id}})" alt="{{__('Edit')}}" title="{{__('Edit')}}"><span class="block"><i class="far fa-edit"></i></span></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div wire:loading wire:target="save">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>
    </div>
</div>
