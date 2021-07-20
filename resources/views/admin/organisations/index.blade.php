@extends('panneau::layout')

@section('content')
    @include('admin.submenu')
    <h1>{{ trans('admin.organisations.title') }}</h1>
    <div class="row">
        <div class="col-sm-12" align="right">
            <a href="{{ Localizer::route('admin.organisations.create') }}" class="btn btn-default">{{ trans('organisation.actions.add') }}</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {!! $list->render() !!}
        </div>
    </div>
    
@endsection
