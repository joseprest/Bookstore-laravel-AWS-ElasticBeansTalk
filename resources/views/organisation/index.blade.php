@extends('layouts.organisation')

@section('content_header')

    <div class="container">
        <h1>{{ trans('screen.screens') }}</h1>
    </div>
    
@endsection

@section('content')
    
    <div class="row">
        <div class="col-sm-12">
            {!! $screensList->render() !!}
        </div>
    </div>
    
    @if(isset($teamList))
    <hr />
    
    <h3>{{ trans('team.team') }}</h3>
    <div class="row">
        <div class="col-sm-12">
            {!! $teamList->render() !!}
        </div>
    </div>
    @endif

@endsection
