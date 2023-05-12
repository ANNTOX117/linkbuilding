@extends('layouts.account')

@section('content')
    <div class="container pt-4">
        <div class="cart-notification">
            <div class="table-title">
                <h1>{{__('Transaction for order: ')}} {{ $order->order }}</h1>
            </div>
            <div class="white-container text-center mt-5">
                @if(!empty($payment))
                    @if($payment->isPaid())
                        <i class="fa fa-check-circle fa-5x text-success mb-5"></i>
                        <p>{{__('Thank you, we have received your payment')}}</p>
                    @else
                        @if($payment->status == 'open')
                            <i class="far fa-clock fa-5x text-muted mb-5"></i>
                        @elseif($payment->status == 'failed')
                            <i class="far fa-times-circle fa-5x text-danger mb-5"></i>
                        @elseif($payment->status == 'canceled')
                            <i class="fas fa-ban fa-5x text-muted mb-5"></i>
                        @elseif($payment->status == 'expired')
                            <i class="far fa-calendar-times fa-5x text-danger mb-5"></i>
                        @else
                            <i class="far fa-times-circle fa-5x text-danger mb-5"></i>
                        @endif
                        <p>{{__('Your transaction could not be processed, the current status is ":status"', ['status' => ucfirst($payment->status)])}}</p>
                    @endif
                @else
                    <i class="fa fa-check-circle fa-5x text-success mb-5"></i>
                    <p>{{__('Thank you, we have received your payment')}}</p>
                @endif
                <a href="{{ route('customer_orders') }}" class="btn btn-primary mt-2 mb-4">{{__('Continue to Orders')}}</a>
            </div>
        </div>
    </div>
@endsection
