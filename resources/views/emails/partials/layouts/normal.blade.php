<tr>
    <td class="section section-two-columns">
        <!--[if (gte mso 9)|(IE)]>
        <table width="100%">
        <tr>
        <td width="50%" valign="top">
        <![endif]-->
        <div class="section-column section-column-left">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="section-column-inner">
                        <table width="100%" class="bubble-cover" align="center" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center">
                                    <img src="{{ array_get($data, 'snippet.picture.link') }}" alt="Cover" width="70%" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <!--[if (gte mso 9)|(IE)]>
        </td><td width="50%" valign="top">
        <![endif]-->
        <div class="section-column section-column-right">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                <tr>
                    <td class="section-column-inner">
                        @if(isset($fields) && sizeof($fields))
                            @include('emails.partials.fields', [
                                'fields' => $fields
                            ])
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </td>
</tr>
