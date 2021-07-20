<!DOCTYPE html>
<html lang="{{ $locale }}-CA">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!--[if !mso]><!-->
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!--<![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <!--[if (gte mso 9)|(IE)]>
            <style type="text/css">
                table {border-collapse: collapse;}
            </style>
        <![endif]-->
    </head>
    <body>
        <!--[if mso]>
            <style type="text/css">
                td {
                    font-family: Arial, sans-serif;
                }
            </style>
        <![endif]-->
        <center class="wrapper">
            <div class="webkit">
                <!--[if (gte mso 9)|(IE)]>
                <table width="700" align="center">
                <tr>
                <td>
                <![endif]-->
                <table class="content" width="700" align="center" cellpadding="0" cellspacing="0" border="0">

                    <!-- Header -->
                    <tr>
                        <td class="section section-header">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td valign="middle" align="center" width="75">&nbsp;</td>
                                    <td valign="middle" align="center">
                                        <img src="{{ $logo }}" alt="Manivelle" width="{{ $logoWidth }}" height="{{ $logoHeight }}" />
                                    </td>
                                    <td valign="middle" align="center" width="75">
                                        <table align="right" class="social-medias" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                @if(isset($facebookLink))
                                                <td>
                                                    <a href="{{ $facebookLink }}" target="_blank" title="Facebook">
                                                        <img src="{{ asset('img/emails/facebook.png') }}" alt="Facebook" width="16" height="16" />
                                                    </a>
                                                </td>
                                                @endif
                                                @if(isset($twitterLink))
                                                <td>
                                                    <a href="{{ $twitterLink }}" target="_blank" title="Twitter">
                                                        <img src="{{ asset('img/emails/twitter.png') }}" alt="Twitter" width="16" height="16" />
                                                    </a>
                                                </td>
                                                @endif
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- End Header -->

                    <!-- Content -->
                    <tr>
                        <td>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <!-- Content Top -->
                                    <td class="section section-basic-infos {{ $topButton ? 'basic-infos-with-cta':'basic-infos-without-cta' }}">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="bubble-type-name">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                         <tr>
                                                             <td>
                                                                 {{ $typeName }}
                                                             </td>
                                                         </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @if ($topButton)
                                                @if($title || $subtitle)
                                                <tr>
                                                    <td class="bubble-title-container">
                                                        @if($title)
                                                        <h1 class="bubble-title">{{ $title }}</h1>
                                                        @endif
                                                        @if($subtitle)
                                                        <h2 class="bubble-subtitle">{{ $subtitle }}</h2>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td class="button">
                                                        <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ $topButton['url'] }}">{{ $topButton['label'] }}</a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                    <!-- End Content Top -->
                                </tr>

                                <!-- Content Main -->
                                @include('emails.partials.layouts.'.$layout)
                                <!-- End Content Main -->

                                <tr>
                                    <!-- Content Bottom -->
                                    @if($bottomButton)
                                        <td class="section section-cta">
                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td class="button">
                                                        <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ $bottomButton['url'] }}">{{ $bottomButton['label'] }}</a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    @endif
                                    <!-- End Content Bottom -->
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- End Content -->

                    <!-- Footer -->
                    <tr>
                        <td class="section section-footer">
                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="footer-note">
                                        {!! array_get($texts, 'footer') !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- End Footer -->

                </table>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </div>
        </center>
    </body>
</html>
