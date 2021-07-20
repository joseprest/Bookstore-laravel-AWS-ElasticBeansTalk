@if(isset($userMessage) && !empty(array_get($userMessage, 'body')))
<tr>
    <td class="section section-message">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="left" valign="top" width="50%" class="message-container">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                        @if(!empty(array_get($userMessage, 'from')))
                        <tr>
                            <td class="message-label">
                            {{ trans('share.email.layouts.card.message_from', [
                                'from' => array_get($userMessage, 'from')
                            ]) }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="message-content">{{ array_get($userMessage, 'body') }}</td>
                        </tr>
                    </table>
                </td>
                <td align="right" valign="top" width="50%" class="message-stamp">
                    <img align="right" src="{{ asset('img/emails/banq/stamp.png') }}" alt="timbre" width="192" />
                </td>
            </tr>
        </table>
    </td>
</tr>
@endif
<tr>
    <td class="section section-image-full">
        <img src="{{ array_get($data, 'snippet.picture.link') }}" alt="Image" width="700" />
    </td>
</tr>
<tr>
    <td class="section section-image-description">
        <table align="center" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>« {{ array_get($data, 'snippet.title') }} »</td>
            </tr>
            <tr>
                <td>{{ array_get($data, 'fields.location.name') }}</td>
            </tr>
        </table>
        
        @if(isset($fields) && sizeof($fields))
            @include('emails.partials.fields', [
                'fields' => $fields
            ])
        @endif
    </td>
</tr>
