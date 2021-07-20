<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" class="bubble-fields">
    @foreach($fields as $fieldKey => $fieldValue)
    <tr>
        <td class="bubble-field field-{{ $fieldKey }}">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="field-label">{{ $fieldValue['label'] }}</td>
                </tr>
                <tr>
                    <td class="field-content">
                        @if (is_array($fieldValue['value']))
                            <table border="0" cellpadding="0" cellspacing="0">
                                @foreach($fieldValue['value'] as $value)
                                    <tr>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            {!! nl2br($fieldValue['value']) !!}
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endforeach
</table>
