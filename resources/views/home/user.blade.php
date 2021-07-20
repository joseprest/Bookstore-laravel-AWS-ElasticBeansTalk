@extends('layouts.main')

@section('content')
    
    <h1>{{ trans('user.form.organisations') }}</h1>
    
    {!! $organisationsList !!}

@endsection
