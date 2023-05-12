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

                    <div class="white-container form-inline mt-2 w-100 mb-3">
                        <div class="col-12 col-md-auto col-lg-auto d-flex align-items-center mb-3 m-md-0 m-lg-0">
                            {{ __('Per Page:') }}&nbsp;
                            <select wire:model="pagination" class="form-control select-xs inputs-small ml-2">
                                <option>2</option>
                                <option>5</option>
                                <option>10</option>
                                <option>15</option>
                                <option>25</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-auto col-lg-auto text-center ml-auto">
                            <input wire:model.debounce.300ms="search" class="form-control inputs-small" type="text" placeholder="{{__('Search')}}...">
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-hover table-payments">
                            <thead>
                            <tr>
                                <th>{{__('Order')}} <a wire:click="sort('order')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Customer')}} <a wire:click="sort('user')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Type')}} <a wire:click="sort('products')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Amount')}} <a wire:click="sort('price')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Date')}} <a wire:click="sort('created_at')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Status')}} <a wire:click="sort('status')"><i class="fas fa-sort"></i></a></th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($payments))
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->order }}</td>
                                        <td>{{ $payment->users->name }} {{ $payment->users->lastname }}</td>
                                        <td class="text-capitalize">{{ $payment->products }}</td>
                                        <td>{{ currency() }} {{ get_price($payment->price) }}</td>
                                        <td>{{ date('Y/m/d', strtotime($payment->created_at)) }}</td>
                                        <td class="text-capitalize">{{ $payment->status }}</td>
                                        <td>
                                            @if(permission('payments', 'update'))
                                                @if(in_array($payment->status, array('open', 'failed')))<a class="blues" wire:click="confirmApprove('{{$payment->order}}')" alt="{{__('Approve payment')}}" title="{{__('Approve payment')}}"><span class="block"><i class="fas fa-check"></i></span></a>@endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have categories added yet')}}</em></div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        @if(!empty($payments))
                            {{ $payments->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="sort, approve, delete">
            <img src="<?php echo Theme::url('img/loading-gif.gif'); ?>" class="loader" />
        </div>

        <div wire:ignore.self class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Approve payment')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p>{{__('Are you sure want to approve this payment?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="approve" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Approve')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Confirm delete')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p>{{__('Are you sure want to delete this payment?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" wire:click.prevent="delete" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('confirmApprove', event => {
            //$('#approveModal').modal('show');
            $('#approveModal').modal({
                backdrop: 'static', 
                keyboard: false
            });
        });

        window.addEventListener('confirmDelete', event => {
            $('#confirmModal').modal('show');
        });
    </script>
@endpush
