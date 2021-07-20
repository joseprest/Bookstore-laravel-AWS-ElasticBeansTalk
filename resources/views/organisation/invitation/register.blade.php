@extends('layouts.main')

@section('content')
    <div class="container">
        <h1 align="center">{{ trans('invitation.linking.title') }}</h1>
        
        <div class="row">
            <div class="col-sm-offset-2 col-sm-8">
                
                <h3>{{ trans('invitation.linking.intro') }}</h3>
                
                {!! $form !!}
                
            </div>
        </div>

    </div>
@endsection
