<tr>
    <td class="section section-image">
        <table align="center" border="0" cellpadding="0" cellspacing="0">
            @if($description = array_get($data, 'snippet.description'))
                <tr>
                    <td>
                        {{ $description }}
                    </td>
                </tr>
            @endif
            <tr>
                <td class="image-container">
                    <img src="{{ array_get($data, 'snippet.picture.link') }}" alt="Image" width="448" />
                </td>
            </tr>
            @if($credits = array_get($data, 'fields.credits'))
                <tr>
                    <td class="image-credits">
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
