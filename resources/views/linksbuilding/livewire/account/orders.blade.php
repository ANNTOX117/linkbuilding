<div class="container-fluid mt-5 px-3 px-lg-5 pt-2">
    <div class="orders-list">
        <h2 class="mt-0">{{__('My orders')}}</h2>
        <div class="table-responsive">
            <table class="table table-default">
                <thead>
                <tr>
                    <th>{{__('Order')}} <a wire:click="sort('order')" class="sort"><i class="fas fa-sort"></i></a></th>
                    <th>{{__('Amount')}} <a wire:click="sort('amount')" class="sort"><i class="fas fa-sort"></i></a></th>
                    <th class="w-250">{{__('Type')}} <a wire:click="sort('products')" class="sort"><i class="fas fa-sort"></i></a></th>
                    <th>{{__('Price')}} <a wire:click="sort('price')" class="sort"><i class="fas fa-sort"></i></a></th>
                    <th>{{__('Payment')}} <a wire:click="sort('status')" class="sort"><i class="fas fa-sort"></i></a></th>
                    <th>{{__('Date')}} <a wire:click="sort('created_at')" class="sort"><i class="fas fa-sort"></i></a></th>
                    <th>{{__('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @if($orders->isNotEmpty())
                    @foreach($orders as $order)
                        <tr wire:loading.remove>
                            <td>
                                @if($selected == $order->order)
                                    <a href="javascript:void(0)" wire:click="details('{{$order->order}}', 'hide')">{{ $order->order }}</a>
                                @else
                                    <a href="javascript:void(0)" wire:click="details('{{$order->order}}', 'show')">{{ $order->order }}</a>
                                @endif
                            </td>
                            <td>{{ $order->amount }}</td>
                            <td class="text-ucfirst">{{ $order->products }}</td>
                            <td>{{ currency() }} {{ get_price($order->price) }}</td>
                            <td class="text-ucfirst">{{ $order->status }}</td>
                            <td>{{ date('Y/m/d H:i', strtotime($order->created_at)) }}</td>
                            <td>
                                <div class="d-flex">
                                    @if($selected == $order->order)
                                        <a href="javascript:void(0)" wire:click="details('{{$order->order}}', 'hide')" class="btn btn-primary mx-1">{{__('Hide details')}}</a>
                                    @else
                                        <a href="javascript:void(0)" wire:click="details('{{$order->order}}', 'show')" class="btn btn-primary mx-1">{{__('Show details')}}</a>
                                    @endif
                                    @if(is_numeric($order->ready) and !empty($order->invoice))
                                        <a href="{{ route('download_invoice', ['name' => $order->order]) }}" class="btn btn-primary mx-1" download>{{__('Invoice')}}</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @if($selected == $order->order)
                        <tr class="opened-details">
                            <td colspan="7">
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
                                    <tbody>
                                        @if(!empty($table))
                                            @foreach($table as $row)
                                                <tr>
                                                    <td>
                                                        {{ remove_http($row['site']) }}
                                                        <p class="text-ghost mb-0">{{ type_page($row['type']) }}</p>
                                                    </td>
                                                    <td>@if(!empty($row['category'])) {{ $row['category'] }} @else <span class="text-ghost">-</span> @endif</td>
                                                    @if(!empty($row['anchor']))
                                                        <td>{{ $row['anchor'] ?? '-' }}</td>
                                                    @else
                                                        <td><span class="text-ghost">-</span></td>
                                                    @endif
                                                    @if($row['item'] == 'startpage article' or $row['item'] == 'blog article')
                                                        <td>
                                                            <div class="row">
                                                                @if(!empty($row['image']))
                                                                    <div class="col-2">
                                                                        <img src="{{ $row['image'] }}" />
                                                                    </div>
                                                                @endif
                                                                <div @if(!empty($row['image'])) class="col-10" @else class="col-12" @endif>
                                                                    {{ $row['title'] }}
                                                                    <br>
                                                                    <small class="text-ghost">{{ get_excerpt($row['content']) }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @else
                                                        <td>{{ $row['title'] }}</td>
                                                    @endif
                                                    <td>{{ $row['url'] }}</td>
                                                    <td class="text-center">
                                                        @if(!empty($row['follow']))
                                                            @if($row['follow'] == 'follow')
                                                                <i class="fas fa-check text-blue"></i>
                                                            @else
                                                                <i class="fas fa-times text-ghost"></i>
                                                            @endif
                                                        @else
                                                            <span class="text-ghost">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(!empty($row['blank']))
                                                            @if(intval($row['blank']) == 1)
                                                                <i class="fas fa-check text-blue"></i>
                                                            @else
                                                                <i class="fas fa-times text-ghost"></i>
                                                            @endif
                                                        @else
                                                            <span class="text-ghost">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ short_date($row['date']) }}
                                                        <p class="text-ghost mb-0">{{ $row['years'] }} {{ plural_or_singular('year', $row['years']) }}</p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">
                            <p class="text-muted mt-3"><em>{{__('You still have no orders')}}</em></p>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            <div wire:loading wire:target="sort" class="white-container text-center text-muted w-100 mb-5">
                <i class="fas fa-spinner fa-spin"></i> {{__('Loading')}}...
            </div>
            @if(!empty($orders))
                {{ $orders->links() }}
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('onShow', event => {
            $('.opened-details').addClass('animated fadeIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){ $(this).removeClass('animated fadeIn'); });
        });
        window.addEventListener('onHide', event => {
            $('.opened-details').hide();
        });
    </script>
@endpush
