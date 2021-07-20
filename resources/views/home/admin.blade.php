@extends('layouts.main')

@section('content')

    <h1>{{ trans('admin.organisations.title') }}</h1>
    
    {!! $organisationsList !!}

@endsection
