<!DOCTYPE html>
<html>
    <head>
        <title>{{ $currentOrganisation->name }} | {{ trans('layout.site_title') }}</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

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
            <div class="content-header">
                @yield('content_header')
            </div>

            <div class="container content-container">
                @yield('content')
            </div>
        </section>
        @show

        @section('footer')
        <footer>
            @include('partials.footer')
        </footer>
        @show

        @include('partials.footer_assets')

    </body>
</html>
