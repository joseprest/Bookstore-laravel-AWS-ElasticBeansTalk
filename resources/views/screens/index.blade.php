@extends('panneau::layout')

@section('content')

    <div class="container-screens">
        <h1>{{ trans('screen.screens') }}</h1>
        <div class="row">
            <div class="col-sm-12">
                {!! $list->render() !!}
            </div>
        </div>
    </div>
    
@endsection
