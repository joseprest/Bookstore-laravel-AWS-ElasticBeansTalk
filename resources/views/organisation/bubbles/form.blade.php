@extends('layouts.organisation')

@section('content_header')

    <div class="container">
        <h1>
            <a href="{{ route(Localizer::routeName('organisation.screens.channel'), [$currentOrganisation->slug, $screen->id, $channel->id]) }}">{{ $channel->snippet->title }}</a>
            <small>/ {{ trans('bubble.creation.title') }}</small>
        </h1>
        
        <hr/>
    </div>

@endsection

@section('content')
        
    {!! $form !!}

@endsection
