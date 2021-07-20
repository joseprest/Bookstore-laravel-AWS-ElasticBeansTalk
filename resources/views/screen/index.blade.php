@extends('layouts.screen')

@section('content')

    <div id="app"></div>

    <script type="text/javascript">
        var IS_BANQ = {{ $isBanq ? 'true':'false' }};
        var MANIVELLE_PROPS = {!! json_encode($manivelleProps) !!};
        var SCREEN = '{{ $screenUrl }}';
        var LOADER_URLS = {!! json_encode($dataUrls) !!};
        var LOADER_BUBBLES_PER_PAGE = {{ config('manivelle.screens.bubbles_per_page') }};
        var INITIAL_TIME = {{ time() * 1000 }};
        var TIMEZONE = 'America/Toronto';
        var LOCALE = '{{ $locale }}';
        @if (!is_null($theme))
            var THEME = '{{ $theme }}';
        @else
            var THEME = null;
        @endif
        @if ($phrases)
            var PHRASES = {!! json_encode($phrases) !!};
        @endif

        // Disabled until the refactor of the frontend
        var START_VIEW = {!! json_encode($startViewProps) !!};
    </script>

@endsection
