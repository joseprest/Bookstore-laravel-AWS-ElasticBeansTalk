<!DOCTYPE html>
<html>
    <head>
        <title>Panneau</title>
        
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        
        {!! $assets->styles() !!}
        
    </head>

    <body>
        @section('header')
        <header>
            @include('panneau::partials.header')
        </header>
        @show
        
        @section('body')
        <div class="container">
            @yield('content')
        </div>
        @show
        
        @section('footer')
        <footer>
            @include('panneau::partials.footer')
        </footer>
        @show

        <div data-react="ModalsGroup"></div>
        
        <script type="text/javascript">
            var PANNEAU_CONFIG = {} || PANNEAU_CONFIG;
            PANNEAU_CONFIG.locale = {{ App::getLocale() }};
            PANNEAU_CONFIG.locales = {!! json_encode(config('locale.locales')) !!};
        </script>
        {!! Panneau::form('picture_infos')->renderSchema() !!}
        {!! $assets->scripts() !!}
    </body>
</html>
