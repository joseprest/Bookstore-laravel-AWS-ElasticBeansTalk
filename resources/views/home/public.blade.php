@extends('layouts.main')
@section('meta')
{{--
    Because the CSRF token expires after a certain time and
    some people may leave the login page opened for a long
    time before login in, we refresh the page after a certain
    delay to ensure an always valid token (else, the user would
    be sent to a "Whoops" page and would have to log in again.)
--}}
<meta http-equiv="refresh" content="{{ Config::get('session.lifetime') * 60 - 60 }}">
@endsection

@section('content')

    <h1 align="center">{{ trans('login.title') }}</h1>

    <hr/>

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                <hr />
            @endif

            {!! $form !!}
            <div style="margin-top: 10px;">
                <a href="{{ route(Localizer::routeName('auth.reset.form'))}}">@lang('login.forgot_link')</a>
            </div>
        </div>
    </div>

@endsection
