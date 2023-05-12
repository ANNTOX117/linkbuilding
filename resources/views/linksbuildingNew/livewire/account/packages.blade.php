<div class="container-fluid">
    @if($view == 'details')
        <div class="package-details">
            @if(!empty($package))
                <h4 class="my-4 font-weight-bold">{{ $info->name }}</h4>
                <div class="row mb-3">
                    <div class="col-6 col-sm-10">
                        <p class="text-muted">{{ $info->description }}</p>
                    </div>
                    <div class="col-6 col-sm-2">
                        <a href="javascript:void(0)" wire:click="showConfig({{$info->id}})" class="btn btn-primary float-right"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-default">
                        <thead>
                        <tr>
                            <th class="minw-100">{{__('Website')}} <a wire:click="sort(0, {{ $info->id }}, 'url')" class="sort"><i class="fas fa-sort"></i></a></th>
                            <th class="minw-100">{{__('IP Address')}} <a wire:click="sort(0, {{ $info->id }}, 'subnet')" class="sort"><i class="fas fa-sort"></i></a></th>
                            <th class="minw-100">{{__('PA')}} <a wire:click="sort(0, {{ $info->id }}, 'pa')" class="sort"><i class="fas fa-sort"></i></a></th>
                            <th class="minw-100">{{__('DA')}} <a wire:click="sort(0, {{ $info->id }}, 'da')" class="sort"><i class="fas fa-sort"></i></a></th>
                            <th class="minw-100">{{__('TF')}} <a wire:click="sort(0, {{ $info->id }}, 'tf')" class="sort"><i class="fas fa-sort"></i></a></th>
                            <th class="minw-100">{{__('CF')}} <a wire:click="sort(0, {{ $info->id }}, 'cf')" class="sort"><i class="fas fa-sort"></i></a></th>
                            <th class="minw-100">{{__('DRE')}} <a wire:click="sort(0, {{ $info->id }}, 'dre')" class="sort"><i class="fas fa-sort"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($package as $item)
                                <tr>
                                    <td>{{ remove_http($item->url) }}</td>
                                    <td>{{ $item->subnet }}</td>
                                    <td>{{ round_price($item->pa) }}</td>
                                    <td>{{ round_price($item->da) }}</td>
                                    <td>{{ round_price($item->tf) }}</td>
                                    <td>{{ round_price($item->cf) }}</td>
                                    <td>{{ round_price($item->dre) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a href="javascript:void(0)" wire:click="showConfig({{$info->id}})" class="btn btn-primary float-right"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
            <a href="javascript:void(0)" wire:click="goBack" class="btn btn-link"><i class="fas fa-long-arrow-alt-left"></i> {{__('Back')}}</a>
        </div>
    @elseif($view == 'configuration')
        <div class="package-configuration">
            <div class="table-title">
                <h4 class="my-4">{{__('Configure links package')}}</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-default">
                    <thead>
                    <tr>
                        <th>{{__('Homepage')}}</th>
                        <th>{{__('Category')}}</th>
                        <th>{{__('Anchor')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('URL')}}</th>
                        <th>{{__('Follow')}}</th>
                        <th>{{__('New tab')}}</th>
                        <th>{{__('Start date')}}</th>
                    </tr>
                    </thead>
                    @if(!empty($package))
                        @foreach($package as $i => $row)
                            <tr>
                                <td class="narrow">
                                    {{ remove_http($row->url) }}
                                    <p class="text-ghost mb-0">{{ type_page($row->type) }}</p>
                                </td>
                                <td class="narrow">
                                    @if(!empty($row->categories))
                                    <div class="form-group mb-0">
                                        <select class="form-control d-inline-block select-xs inputs mr-2 is_category" data-package="{{ $row->id }}" wire:ignore>
                                            <option value="">{{__('Choose category')}}</option>
                                            @foreach($row->categories as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="narrow">
                                    <input type="text" class="form-control inputs-small is_anchor" placeholder="{{__('Link anchor')}}" autocomplete="off" wire:model="package_anchors.{{ $row->id }}" />
                                    @error('package_anchors.'.$row->id) <span class="error">{{ $message }}</span> @enderror
                                </td>
                                <td class="narrow">
                                    <input type="text" class="form-control inputs-small is_title" placeholder="{{__('Link title')}}" autocomplete="off" wire:model="package_titles.{{ $row->id }}" />
                                    @error('package_titles.'.$row->id) <span class="error">{{ $message }}</span> @enderror
                                </td>
                                <td class="narrow">
                                    <input type="url" class="form-control inputs-small is_url" placeholder="{{__('Link url')}}" autocomplete="off" wire:model="package_urls.{{ $row->id }}" />
                                    @error('package_urls.'.$row->id) <span class="error">{{ $message }}</span> @enderror
                                </td>
                                <td class="narrow text-center">
                                    <input id="package_follows.{{ $row->id }}" wire:model="package_follows.{{ $row->id }}" type="checkbox" class="styled-checkbox">
                                    <label for="package_follows.{{ $row->id }}">&nbsp;</label>
                                    @error('package_follows.'.$row->id) <span class="error">{{ $message }}</span> @enderror
                                </td>
                                <td class="narrow text-center">
                                    <input id="package_blanks.{{ $row->id }}" wire:model="package_blanks.{{ $row->id }}" type="checkbox" class="styled-checkbox">
                                    <label for="package_blanks.{{ $row->id }}">&nbsp;</label>
                                    @error('package_blanks.'.$row->id) <span class="error">{{ $message }}</span> @enderror
                                </td>
                                <td class="narrow">
                                    <input type="text" class="form-control inputs-small inputs-datepicker datepicker" placeholder="{{__('Select the date')}}" autocomplete="off" data-date-format="dd.mm.yyyy" data-package="{{ $row->id }}" wire:ignore />
                                    @error('package_dates.'.$row->id) <span class="error">{{ $message }}</span> @enderror
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>

            @if($completed)
                <a href="javascript:void(0)" wire:click="goBack" class="btn btn-link"><i class="fas fa-long-arrow-alt-left"></i> {{__('Back to packages')}}</a>
            @else
                <div class="inline-block float-right">
                    <a href="javascript:void(0)" wire:click="doOrder" wire:loading.remove class="btn btn-primary"><i class="fas fa-cart-plus"></i> {{__('Add to cart')}}</a>
                    <div wire:loading wire:target="doOrder">
                        <a href="javascript:void(0)" class="btn btn-primary btn-loading float-right"><i class="fas fa-spinner fa-spin"></i> {{__('Loading')}}...</a>
                    </div>
                </div>
                <a href="javascript:void(0)" wire:click="goBack" class="btn btn-link"> {{__('Cancel')}}</a>
            @endif
        </div>
    @else
        <div class="package-list">
            @if($categories->isNotEmpty())
                @foreach($categories as $i => $category)
                    <h4 class="my-4 font-weight-bold">{{ $category->name }}</h4>
                    <div class="table-responsive">
                        <table class="table table-default">
                            <thead>
                            <tr>
                                <th class="minw-100">{{__('Name')}} <a wire:click="sort({{ $i }}, {{ $category->id }}, 'name')" class="sort"><i class="fas fa-sort"></i></a></th>
                                <th class="minw-100">{{__('Number')}} <a wire:click="sort({{ $i }}, {{ $category->id }}, 'sites')" class="sort"><i class="fas fa-sort"></i></a></th>
                                <th class="minw-100">{{__('PA')}} <a wire:click="sort({{ $i }}, {{ $category->id }}, 'pa')" class="sort"><i class="fas fa-sort"></i></a></th>
                                <th class="minw-100">{{__('DA')}} <a wire:click="sort({{ $i }}, {{ $category->id }}, 'da')" class="sort"><i class="fas fa-sort"></i></a></th>
                                <th class="minw-100">{{__('TF')}} <a wire:click="sort({{ $i }}, {{ $category->id }}, 'tf')" class="sort"><i class="fas fa-sort"></i></a></th>
                                <th class="minw-100">{{__('CF')}} <a wire:click="sort({{ $i }}, {{ $category->id }}, 'cf')" class="sort"><i class="fas fa-sort"></i></a></th>
                                <th class="minw-100">{{__('Total')}} <a wire:click="sort({{ $i }}, {{ $category->id }}, 'total')" class="sort"><i class="fas fa-sort"></i></a></th>
                                <th class="minw-100">{{__('Price')}} <a wire:click="sort({{ $i }}, {{ $category->id }}, 'price')" class="sort"><i class="fas fa-sort"></i></a></th>
                                <th class="minw-100">{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($category->packages))
                                @foreach($category->packages as $package)
                                    <tr>
                                        <td><a href="javascript:void(0)" wire:click="showPackage({{$package->id}})">{{ $package->name }}</a></td>
                                        <td>{{ $package->sites }}</td>
                                        <td>{{ round_price($package->pa) }}</td>
                                        <td>{{ round_price($package->da) }}</td>
                                        <td>{{ round_price($package->tf) }}</td>
                                        <td>{{ round_price($package->cf) }}</td>
                                        <td><del>{{ currency() }} {{ get_price($package->total) }}</del></td>
                                        <td><span class="price">{{ currency() }} {{ get_price($package->price) }}</span></td>
                                        <td>
                                            <a href="javascript:void(0)" wire:click="showPackage({{$package->id}})" class="btn btn-primary"><i class="fas fa-shopping-cart"></i>  {{__('Order now')}}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @else
                <div class="white-container text-center">
                    <p class="text-muted mt-3"><em>{{__('No packages yet')}}</em></p>
                </div>
            @endif
        </div>
    @endif

</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            //
        });
        window.addEventListener('doComplete', event => {
            dialogConfirm(event.detail.message, event.detail.confirm, event.detail.cancel, event.detail.redirect);
        });
        window.addEventListener('onDetails', event => {
            $('.package-details').addClass('animated fadeIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){ $(this).removeClass('animated fadeIn'); });
        });
        window.addEventListener('onConfig', event => {
            $('.package-configuration').addClass('animated fadeIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){ $(this).removeClass('animated fadeIn'); });
        });
        window.addEventListener('onPurchase', event => {
            $('.package-purchase').addClass('animated fadeIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){ $(this).removeClass('animated fadeIn'); });
        });
        window.addEventListener('onBack', event => {
            $('.package-list').addClass('animated fadeIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){ $(this).removeClass('animated fadeIn'); });
        });
        window.addEventListener('doAlert', event => {
            dialogAlert(event.detail.message, event.detail.cancel);
        });
        window.addEventListener('countCategories', event => {
            @this.set('max_fields', $('.is_title').length);
            @this.set('max_categories', $('.is_category').length);
        });
        window.addEventListener('loadDatepicker', event => {
            $('.datepicker').datepicker('destroy')
            $('.datepicker').datepicker({
                autoHide: true,
                format: 'dd-mm-yyyy',
                startDate: Date.now()
            });
        });
        $(document).on('change', '.is_category', function() {
            @this.categoryUpdated($(this).data('package'), $(this).val());
        });
        $(document).on('change', '.datepicker', function() {
            @this.dateUpdated($(this).data('package'), $(this).val());
        });
    </script>
@endpush
