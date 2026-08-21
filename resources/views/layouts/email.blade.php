{{--
    Email Layout Shell — rendered for system email notifications & broadcasts
    Usage: <x-email-layout title="..." preheader="...">
               {{ $slot }}
           </x-email-layout>
--}}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      xmlns="http://www.w3.org/1999/xhtml"
      xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="format-detection" content="telephone=no,date=no,address=no,email=no,url=no" />
    <meta name="color-scheme" content="light" />
    <meta name="supported-color-schemes" content="light" />
    <title>{{ $title ?? config('app.name', 'Master Notification') }}</title>

    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
                <o:AllowPNG/>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->

    <style type="text/css">
        /* Client Resets */
        body, #bodyTable {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        body {
            background-color: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        table, td {
            border-collapse: collapse !important;
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        /* Mobile Responsiveness */
        @media only screen and (max-width: 620px) {
            .email-wrapper {
                padding: 16px 8px !important;
            }
            .email-card {
                width: 100% !important;
                border-radius: 12px !important;
            }
            .email-header {
                padding: 24px 20px !important;
            }
            .email-body {
                padding: 24px 20px !important;
            }
            .email-footer-cell {
                padding: 20px !important;
            }
        }
    </style>
</head>
<body id="body" style="margin: 0; padding: 0; background-color: #f8fafc; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">

    @if (!empty($preheader))
    <div aria-hidden="true" style="display: none; font-size: 1px; color: #f8fafc; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all;">
        {{ $preheader }}&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
    </div>
    @endif

    <table id="bodyTable" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f8fafc; min-width: 100%; margin: 0; padding: 0;">
        <tr>
            <td class="email-wrapper" align="center" valign="top" style="padding: 40px 16px;">
                <table class="email-card" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);">
                    <!-- Email Brand Header -->
                    <tr>
                        <td class="email-header" align="center" style="padding: 32px 32px 24px; background-color: #0f172a; border-bottom: 3px solid #10b981;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="background-color: #10b981; border-radius: 12px; width: 44px; height: 44px; font-weight: 900; color: #ffffff; font-size: 22px; line-height: 44px; text-align: center;">
                                        M
                                    </td>
                                    <td style="padding-left: 12px;">
                                        <span style="font-size: 18px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; display: block;">{{ config('app.name', 'Master Admin') }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Email Body Slot -->
                    <tr>
                        <td class="email-body" style="padding: 36px 32px; color: #334155; font-size: 14px; line-height: 1.6;">
                            {{ $slot }}
                        </td>
                    </tr>

                    <!-- Email Footer -->
                    <tr>
                        <td class="email-footer-cell" align="center" style="padding: 24px 32px; background-color: #f1f5f9; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b;">
                            <p style="margin: 0 0 6px 0; font-weight: 600;">&copy; {{ date('Y') }} {{ config('app.name', 'Master Admin') }}. All rights reserved.</p>
                            <p style="margin: 0; font-size: 11px; color: #94a3b8;">This is an automated system notification.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
