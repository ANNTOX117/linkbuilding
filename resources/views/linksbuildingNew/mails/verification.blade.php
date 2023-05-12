<!DOCTYPE html>
<html>
<head>
    <title>Mailing</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        @media screen {
            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 400;
                src: local('Lato Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 700;
                src: local('Lato Bold'), local('Lato-Bold'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 400;
                src: local('Lato Italic'), local('Lato-Italic'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 700;
                src: local('Lato Bold Italic'), local('Lato-BoldItalic'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format('woff');
            }
        }

        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        @media screen and (max-width:600px) {
            h1 {
                font-size: 32px !important;
                line-height: 32px !important;
            }
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>
<body style="background-color: #b53471; margin: 0 !important; padding: 0 !important;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fff;background-color:#fff;width:100%;">
    <tr>
        <td bgcolor="#fff" align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="center" valign="top" style="padding: 40px 10px 40px 10px;">
                        <img src="{{ theme_url('img/logo.png') }}" alt="" width="240" height="76" style="display: block; border: 0px;" >
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#fff" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; max-width: 600px; border-top: 3px solid; border-right: 3px solid; border-left: 3px solid; border-color: #b53471;">
                <tr>
                    <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#b53471" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; max-width: 600px; border-right: 3px solid; border-left: 3px solid; border-color: #b53471;">
                <tr>
                    <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <p style="margin: 0;">{{__('Hi')}}, <strong>{{ @$name }}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <p style="margin: 0;">{{__('verify_message')}}</p>
                    </td>
                </tr>
                
                {{-- <tr>
                    <td class="pink-button text-button" style="background:#18bc9c; color:#c1cddc; font-family:'Muli', Arial,sans-serif; font-size:18px; line-height:25px; padding:12px 30px; text-align:center; border-radius:0px 22px 22px 22px; font-weight:bold;">
                        <a href="{{ @$link }}" target="_blank" class="link-white" style="color:#ffffff; text-decoration:none;"><span class="link-white" style="color:#ffffff; text-decoration:none;">{{__('Verify email address')}}</span></a>
                    </td>
                </tr> --}}

                <tr>
                    <td bgcolor="#ffffff" align="left">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 60px 30px;">
                                    <table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="center" style="border-radius: 3px;" bgcolor="#18bc9c">
                                                <a href="{{ @$link }}" target="_blank" style="font-size: 20px; font-family: Helvetica, Arial, sans-serif; text-decoration: none; padding: 15px 25px; border-radius: 2px; color: #B53471; background: #fff; border: 2px solid #B53471;">
                                                    @lang('Verify email address')
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
     <tr>
        <td bgcolor="#b53471" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#b53471" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td bgcolor="#b53471" align="center" style="padding: 0px 30px 30px 30px; color: #ffffff; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;"> <br>
                        <p style="margin: 0;">@lang('Copyright') {{ date('Y') }} <a href="{{ config('app.url') }}" target="_blank" style="color: #000; font-weight: 700;">{{ config('app.name') }}</a>.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
