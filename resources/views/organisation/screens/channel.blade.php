@extends('layouts.organisation')

@section('content_header')

    <div class="container">
        <h1>
            <a href="{{ route(Localizer::routeName('organisation.home'), [$currentOrganisation->slug]) }}">{{ trans('screen.screens') }}</a>
            <small>/ <a href="{{ route(Localizer::routeName('organisation.screens.channels'), [$currentOrganisation->slug, $item->id]) }}">{{ $item->name }}</a></small>
            <small>/ {{ $channel->snippet->title }}</small>
        </h1>
        
        <div class="row">
            <div class="col-sm-6">
                <a href="{{ route(Localizer::routeName('organisation.screens.channels'), [$currentOrganisation->slug, $item->id]) }}" class="btn btn-default">&laquo; {{ trans('channel.back_to_channels') }}</a>
            </div>
            
            <div class="col-sm-6 text-right">
                @if($channel->canAddBubbles())
                <a href="{{ route(Localizer::routeName('organisation.bubbles.create'), [$currentOrganisation->slug, $item->id, $channel->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> {{ trans('channel.actions.add_content') }}</a>
                @endif
            </div>
        </div>
        
        <hr/>
    </div>

@endsection


@section('content')
    
    {!! $list !!}
    
@endsection
