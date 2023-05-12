<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=abeezee" rel="stylesheet">
    <title>{{__('Invoice')}} {{ $code }}</title>
    <style>
        body{
            text-align: center;
            color:#777;
            font-family:"abeezee", sans-serif;
            font-size:12px;
        }
        body h1{
            font-weight:300;
            margin-bottom:0px;
            padding-bottom:0px;
            color:#222;
            font-size:12px;
        }

        body h3{
            font-weight:300;
            margin-top:10px;
            margin-bottom:20px;
            font-style:italic;
            color:#555;
        }
        body a{
            color:#777;
        }
        .watermark {
            position:absolute;
            padding-top:150px;
            width:85%;
            height:100%;
        }
        .invoice-box table{
            width:100%;
            line-height:inherit;
            text-align:left;
        }
        .invoice-box table td{
            padding:5px;
            vertical-align:top;
        }
        .invoice-box table tr.top table td{
            padding-bottom:20px;
        }
        .invoice-box table tr.top table td.title{
            font-size:45px;
            line-height:45px;
            color:#333;
        }
        .invoice-box table tr.information table td{
            padding-bottom:40px;
        }
        .invoice-box table tr.heading td{
            background:#eee;
            border-bottom:1px solid #0088ff;
            font-weight:bold;
        }
        .invoice-box table tr.details td{
            padding-bottom:20px;
        }
        .invoice-box table tr.item td{
            border-bottom:1px solid #eee;
        }
        .invoice-box table tr.item.last td{
            border-bottom:none;
        }
        @page {
            header: page-header;
            footer: html_myFooter;
        }
        tr.even{
            background-color:#f9f9f9;
        }
    </style>
</head>

<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">

        <tr class="information">
            <td colspan="3">
                <table>
                    <tr>
                        <td colspan="3" style="width:55%; color:#222">
                            @if(!empty($header))
                                {!! nl2br($header) !!}
                            @endif
                        </td>
                        <td>
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <table>
                    <tr>
                        <td style="text-align: right">
                            <img src="{{ $logo }}" />
                        </td>
                        <td style="text-align: left; padding-top: 20px">
                            <h1 style="color: #222222">{{ env('APP_NAME') }}</h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr style="height:5px;">
            <td colspan="5"><br><br></td>
        </tr>

        <tr style="margin-bottom:5px;">
            <td colspan="3" >
                <table >
                    <tr>
                        <td>
                            @if(!empty($user->name)){{ $user->name }}@endif @if(!empty($user->lastname)){{ $user->lastname }}@endif <br>
                            @if(!empty($user->address)){{ $user->address }} <br>@endif
                            @if(!empty($user->postal_code)){{ $user->postal_code }}@endif @if(!empty($user->city)){{ $user->city }}@endif <br>
                            @if(!empty($user->countries)){{ $user->countries->name }}@endif
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <table style="border-collapse: collapse;">
                    <tr>
                        <td style="color:#222;">{{__('Invoice')}}:</td>
                        <td><b>#{{ $code }}</b></td>
                    </tr>
                    @if(strpos($payment, 'tr_') !== false)
                        <tr>
                            <td style="color:#222;">{{__('Transaction ID')}}:</td>
                            <td><b>{{ $payment }}</b></td>
                        </tr>
                    @endif
                    <tr>
                        <td style="color:#222;">{{__('Client')}}:</td>
                        <td><b>{{ str_pad($user->id, 4, 0, STR_PAD_LEFT) }}</b></td>
                    </tr>
                    <tr>
                        <td style="color:#222;">{{__('Date')}}:</td>
                        <td><b>{{ short_date($date) }}</b></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr style="height:5px;">
            <td colspan="5"><br><br><br><br></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="padding:5px;">
                <table style="border-collapse: collapse;">
                    <tr class="">
                        <td style="color:#222;border-bottom:1px solid #007bff;">
                            {{__('Number')}}
                        </td>
                        <td style="width:40%;color:#222;border-bottom:1px solid #007bff;">
                            {{__('Description')}}
                        </td>
                        <td style="width:17%;color:#222;border-bottom:1px solid #007bff;">
                            {{__('Price')}}
                        </td>
                        <td style="color:#222;border-bottom:1px solid #007bff;">
                            {{__('VAT')}}
                        </td>
                        <td style="color:#222;border-bottom:1px solid #007bff;text-align: right">
                            {{__('Subtotal')}}
                        </td>
                    </tr>

                    @foreach($order as $item)
                        <tr class="item even">
                            <td>
                                {{ $item->amount }}
                            </td>
                            <td>
                                {{__('Product') }} : {{ ucfirst($item->products) }} <br><br>
                                @if(!empty($settings) and $settings->value == 1)
                                    @php $details = \App\Models\Order::get_details($item->order); @endphp
                                    @if(!empty($details))
                                        @foreach($details as $detail)
                                            @php $detail = json_decode($detail->details, true); @endphp
                                            @if(json_last_error() === JSON_ERROR_NONE)
                                                {{__('Website') }}          : {{ @\App\Models\AuthoritySite::find($detail['authority'])->url }} <br>
                                                {{__('Section') }}          : {{ @\App\Models\Category::find($detail['category'])->name }} <br>
                                                {{__('URL') }}              : {{ $detail['url'] }} <br>
                                                {{__('Anchor') }}           : {{ $detail['anchor'] }} <br>
                                                {{__('Title') }}            : {{ $detail['title'] }} <br>
                                                {{__('Publication Date') }} : {{ date('d-m-Y', strtotime($detail['date'])) }} <br>
                                                {{__('Years') }}            : {{ $detail['years'] }} <br><br>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            </td>
                            <td>
                                {{ $user->countries->symbol }} {{ get_price($item->partial) }}
                            </td>
                            <td>
                                {{ $user->countries->symbol }} {{ $item->tax }}
                            </td>
                            <td style="text-align: right">
                                {{ $user->countries->symbol }} {{ get_price($item->total) }}
                            </td>
                        </tr>
                    @endforeach

                    <tr class="total odd">
                        <td colspan="4">
                        </td>
                        <td style="text-align: right">
                            {{ $user->countries->symbol }} {{ get_price($item->total) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<htmlpagefooter name="myFooter">
    <table style="border-collapse: collapse; width: 100%">
        <tr class="">
            <td style="color:#18181f;border-top:1px solid #007bff; text-align: center"><br>{{__('Page')}} {PAGENO} / {nbpg}</td>
        </tr>
    </table>
</htmlpagefooter>
</body>
</html>
