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
            @if($credits = array_get($data, 'fields.credits'))
            <tr>
                <td>
                    {{ trans('share.email.layouts.photo.credit', [
                        'credit' => $credits
                    ]) }}
                </td>
            </tr>
            @endif
        </table>
        
        @if(isset($fields) && sizeof($fields))
            @include('emails.partials.fields', [
                'fields' => $fields
            ])
        @endif
    </td>
</tr>
