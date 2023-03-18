<!DOCTYPE html>

<html lang="en">

<head>
    <title></title>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
            <o:AllowPNG />
        </o:OfficeDocumentSettings>
    </xml><![endif]-->
    <!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css?family=Cormorant+Garamond" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Quattrocento" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather" rel="stylesheet" type="text/css" />
    <!--<![endif]-->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: inherit !important;
        }

        #MessageViewBody a {
            color: inherit;
            text-decoration: none;
        }

        p {
            line-height: inherit
        }

        @media (max-width: 620px) {
            .desktop_hide table.icons-inner {
                display: inline-block !important;
            }

            .icons-inner {
                text-align: center;
            }

            .icons-inner td {
                margin: 0 auto;
            }

            .row-content {
                width: 100% !important;
            }

            .mobile_hide {
                display: none;
            }

            .stack .column {
                width: 100%;
                display: block;
            }

            .mobile_hide {
                min-height: 0;
                max-height: 0;
                max-width: 0;
                overflow: hidden;
                font-size: 0px;
            }

            .desktop_hide,
            .desktop_hide table {
                display: table !important;
                max-height: none !important;
            }
        }
    </style>
</head>

<body style="background-color: #FFFFFF; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
<table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation"
       style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF;" width="100%">
    <tbody>
    <tr>
        <td>
            @component('inisiatif::mail.component.header', ['foundationLogo' => $foundationLogo])
                KONFIRMASI PEMBAYARAN
            @endcomponent
            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2"
                   role="presentation"
                   style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f7f6f5;" width="100%">
                <tbody>
                <tr>
                    <td>
                        <table align="center" border="0" cellpadding="0" cellspacing="0"
                               class="row-content stack" role="presentation"
                               style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fff; color: #000000; width: 600px;"
                               width="600">
                            <tbody>
                            <tr>
                                <td class="column"
                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                    width="100%">
                                    <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                           role="presentation"
                                           style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                           width="100%">
                                        <tr>
                                            <td style="padding-bottom:10px;padding-left:15px;padding-right:15px;padding-top:35px;">
                                                <div style="font-family: Tahoma, Verdana, sans-serif">
                                                    <div style="font-size: 12px; font-family: 'Lato', Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #222222; line-height: 1.5;">
                                                        <p style="margin: 0; font-size: 16px; text-align: left; mso-line-height-alt: 24px;">
                                                            <span style="font-size:16px;">
                                                                <strong>Assalamu'alaikum, Bapak / Ibu {{ $donorName }}</strong>
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                           role="presentation"
                                           style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                           width="100%">
                                        <tr>
                                            <td style="padding-bottom:10px;padding-left:15px;padding-right:15px;padding-top:10px;">
                                                <div style="font-family: Tahoma, Verdana, sans-serif">
                                                    <div style="font-size: 12px; font-family: 'Lato', Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #222222; line-height: 1.5;">
                                                        <p style="margin: 0; font-size: 16px; text-align: left;">
                                                            Terima kasih atas kepercayaan Bapak / Ibu
                                                            {{ $donorName }} telah menyalurkan zakat, infaq
                                                            dan sedekahnya melalui Kami.
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                           role="presentation"
                                           style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                           width="100%">
                                        <tr>
                                            <td style="padding-bottom:35px;padding-left:15px;padding-right:15px;padding-top:10px;">
                                                <div style="font-family: Tahoma, Verdana, sans-serif">
                                                    <div style="font-size: 12px; font-family: 'Lato', Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #222222; line-height: 1.5;">
                                                        <p style="margin: 0; mso-line-height-alt: 24px;">
                                                            <span style="font-size:16px;">
                                                                Semoga Allah memberikan pahala atas apa yang telah
                                                                Bapak/Ibu {{ $donorName }} tunaikan, semoga Allah
                                                                memberikan keberkahan atas harta yang masih tertinggal
                                                                dan semoga zakat, infaq dan sedekah ini menjadi
                                                                pembersih bagi jiwa dan harta Bapak/Ibu
                                                                {{ $donorName }} beserta keluarga.
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3 desktop_hide"
                   role="presentation"
                   style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; mso-hide: all; display: none; max-height: 0; overflow: hidden; background-color: #f7f6f5;"
                   width="100%">
                <tbody>
                <tr>
                    <td>
                        <table align="center" border="0" cellpadding="0" cellspacing="0"
                               class="row-content stack" role="presentation"
                               style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; mso-hide: all; display: none; max-height: 0; overflow: hidden; background-color: #fff; color: #000000; width: 600px;"
                               width="600">
                            <tbody>
                            <tr>
                                <td class="column"
                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                    width="100%">
                                    <table border="0" cellpadding="10" cellspacing="0"
                                           class="text_block" role="presentation"
                                           style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word; mso-hide: all; display: none; max-height: 0; overflow: hidden;"
                                           width="100%">
                                        <tr>
                                            <td>
                                                <div style="font-family: sans-serif">
                                                    <div style="font-size: 14px; mso-line-height-alt: 16.8px; color: #555555; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                        <p style="margin: 0; font-size: 14px;">
                                                            <span style="font-size:16px;">
                                                                <strong>Nomor Transaksi : {{ $transactionNumber }}<br /></strong>
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4 desktop_hide"
                   role="presentation"
                   style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; mso-hide: all; display: none; max-height: 0; overflow: hidden; background-color: #f7f6f5;"
                   width="100%">
                <tbody>
                <tr>
                    <td>
                        <table align="center" border="0" cellpadding="0" cellspacing="0"
                               class="row-content stack" role="presentation"
                               style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; mso-hide: all; display: none; max-height: 0; overflow: hidden; background-color: #fff; color: #000000; width: 600px;"
                               width="600">
                            <tbody>
                            <tr>
                                <td class="column"
                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                    width="100%">
                                    <table border="0" cellpadding="10" cellspacing="0"
                                           class="text_block" role="presentation"
                                           style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word; mso-hide: all; display: none; max-height: 0; overflow: hidden;"
                                           width="100%">
                                        <tr>
                                            <td>
                                                <div style="font-family: sans-serif">
                                                    <div style="font-size: 14px; mso-line-height-alt: 16.8px; color: #555555; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                        <p style="margin: 0; font-size: 14px;">
                                                            <span style="font-size:16px;">
                                                                <strong>Jumlah Pembayaran : {{ \number_format($transactionAmount) }}<br /></strong>
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            @include('inisiatif::mail.component.payment')
            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-9"
                   role="presentation"
                   style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f7f6f5;" width="100%">
                <tbody>
                <tr>
                    <td>
                        <table align="center" border="0" cellpadding="0" cellspacing="0"
                               class="row-content stack" role="presentation"
                               style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fff; color: #000000; width: 600px;"
                               width="600">
                            <tbody>
                            <tr>
                                <td class="column"
                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                    width="100%">
                                    <table border="0" cellpadding="0" cellspacing="0" class="text_block"
                                           role="presentation"
                                           style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                           width="100%">
                                        <tr>
                                            <td style="padding-bottom:35px;padding-left:15px;padding-right:15px;padding-top:35px;">
                                                <div style="font-family: Tahoma, Verdana, sans-serif">
                                                    <div style="font-size: 12px; font-family: 'Lato', Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #222222; line-height: 1.5;">
                                                        <p style="margin: 0; mso-line-height-alt: 24px;">
                                                            <span style="font-size:16px;">
                                                                Jika menghadapi kendala mengenai pembayaran
                                                                dan konfirmasi, silakan langsung menghubungi
                                                                {{ $foundationName }} ditelepon {{ $foundationContactNumber }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            @include('inisiatif::mail.component.footer')
        </td>
    </tr>
    </tbody>
</table><!-- End -->
</body>

</html>
