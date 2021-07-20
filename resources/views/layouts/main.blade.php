<!DOCTYPE html>
<html>
    <head>
        <title>{{ trans('layout.site_title') }}</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('meta')

        {!! $assets->styles() !!}

        {!! Asset::container('header')->styles() !!}
        {!! Asset::container('header')->scripts() !!}
    </head>
    <body>

        @include('partials.analytics')

        @section('header')
        <header>
            @include('partials.header')
        </header>
        @show

        @section('content_container')
        <section id="content">
            <div data-react="PendingTasksAlert"></div>
            <div class="container">
                @yield('content')
            </div>
        </section>
        @show

        @section('footer')
        <footer>
            <div class="container">
                @include('partials.footer')
            </div>
        </footer>
        @show

        @include('partials.footer_assets')

    </body>
</html>
