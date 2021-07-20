@extends('panneau::layout')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                {!! $list->render() !!}
            </div>
        </div>
    </div>
    
@endsection
