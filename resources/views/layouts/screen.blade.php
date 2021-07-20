<!DOCTYPE html>
<html>
    <head>
        <title>Manivelle</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        {!! Asset::container('header')->styles() !!}
        {!! Asset::container('header')->scripts() !!}

        <script type="text/javascript">
            var PUBNUB_SUBSCRIBE_KEY = '{{ config('services.pubnub.subscribe_key') }}';
            var PUBNUB_NAMESPACE = '{{ config('services.pubnub.namespace') }}';
            var TRACKING_ID = '{{ config('services.google.analytics_screen') }}';
        </script>

        @include('partials.analytics_fmc')
    </head>
    <body>

        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', TRACKING_ID, 'auto', 'main');
            ga('main.send', 'pageview');
        </script>

        @yield('content')

        {!! Asset::container('footer')->scripts() !!}

        <!--Start: DTM footer to be placed at bottom of the body-->
        <script type="text/javascript">_satellite.pageBottom();</script>
        <!--End: DTM footer to be placed at bottom of the body-->
    </body>
</html>
