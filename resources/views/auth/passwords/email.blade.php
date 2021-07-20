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

<!-- Main Content -->
@section('content')
    <h1 align="center">{{ trans('login.title_reset') }}</h1>

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
        </div>
    </div>
@endsection
