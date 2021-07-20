<script type="text/javascript">

    var PANNEAU_CONFIG = PANNEAU_CONFIG || {};
    PANNEAU_CONFIG.locale = '{{ App::getLocale() }}';
    PANNEAU_CONFIG.pubnub = {
        subscribe_key: '{{ config('broadcasting.connections.pubnub.subscribe_key') }}',
        namespace: '{{ config('broadcasting.connections.pubnub.namespace') }}'
    };
    @if($graphQlUrl)
    PANNEAU_CONFIG.graphQL = {
        url: '{{ $graphQlUrl }}'
    };
    @endif
    @if($localizedStrings)
    LOCALIZED_STRINGS = {!! json_encode($localizedStrings) !!};
    @endif

</script>

{!! $assets->scripts() !!}

{!! Asset::container('footer')->scripts() !!}

@if($urls)
<script type="text/javascript">

    URL.setRoutes({!! json_encode($urls) !!});

</script>
@endif;

<div data-react="ModalsGroup"></div>
