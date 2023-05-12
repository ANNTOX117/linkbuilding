<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=abeezee" rel="stylesheet">
    <title>{{__('General conditions')}}</title>
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
            color:#777;
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
            <td style="width:100%; color:#222; border-bottom:1px solid #0063fa; text-transform:uppercase; text-align: center">
                {{ $title }}
            </td>
        </tr>
        <tr style="height:5px;">
            <td colspan="5"><br><br></td>
        </tr>
        <tr class="information">
            <td style="width:100%; color:#444;">
                {!! $text !!}
            </td>
        </tr>
    </table>
</div>
<htmlpagefooter name="myFooter">
    <table style="border-collapse: collapse; width: 100%">
        <tr class="">
            <td style="color:#18181f;border-top:1px solid #0063fa; text-align: center"><br>@lang('Page') {PAGENO} / {nbpg}</td>
        </tr>
    </table>
</htmlpagefooter>
</body>
</html>
