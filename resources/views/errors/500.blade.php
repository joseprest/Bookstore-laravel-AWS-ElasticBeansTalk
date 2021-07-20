<!DOCTYPE html>
<html>
    <head>
        <title>{{ trans('layout.site_title') }}</title>
        
        <link href="{{ asset('/css/bootstrap.css') }}" type="text/css" rel="stylesheet" />
        <link href="{{ asset('/css/main.css') }}" type="text/css" rel="stylesheet" />
        
        @if(isset($debug) && isset($css) && $debug)
            <style>
                {!! $css !!}
            </style>
        @endif
    </head>
    <body>
        
        @include('partials.analytics')
        
        <header>
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="navbar-col navbar-title">
                        <a class="navbar-brand" href="/">
                            <span class="logo"></span>
                        </a>
                    </div>
                </div>
            </nav>

        </header>
        
        <section id="content">
            <div class="container">
                @if(isset($debug) && isset($content) && $debug)
                    {!! $content !!}
                @else
                    <h1 align="center">{{ trans('error_pages.500.title') }}</h1>
                @endif
            </div>
        </section>
    </body>
</html>
