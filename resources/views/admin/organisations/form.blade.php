@extends('panneau::layout')

@section('content')
    
    @if(isset($item))
        <h1>{{ trans('organisation.edition.title') }}</h1>
    @else
        <h1>{{ trans('organisation.creation.title') }}</h1>
    @endif
    
    {!! $form !!}

@endsection
