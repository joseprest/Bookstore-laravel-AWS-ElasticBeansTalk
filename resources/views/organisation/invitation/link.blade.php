@extends('layouts.main')

@section('content')
    <div class="container">
        <h1 align="center">{{ trans('invitation.linking.title') }}</h1>
        
        <div class="row">
            <div class="col-sm-offset-2 col-sm-8" align="center">
                
                <h3>{{ trans('invitation.linking.intro') }}</h3>
                
                <hr/>
                
                {!! Form::open([
                    'route' => [Localizer::routeName('organisation.invitation.link'), $organisation->slug, $invitation->invitation_key],
                    'method' => 'POST'
                ]) !!}
                
                <button type="submit" class="btn btn-primary btn-lg">{{ trans('invitation.actions.accept') }}</button>
                
                {!! Form::close() !!}
            </div>
        </div>

    </div>
@endsection
