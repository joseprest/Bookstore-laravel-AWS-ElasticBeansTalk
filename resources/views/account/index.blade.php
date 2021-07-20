@extends('layouts.main')

@section('content')

    <h1>{{ $item->name }}</h1>
    
    <div class="row">
        <div class="col-xs-4 col-sm-3">
            <div data-react="UserAvatar" data-user="{{ json_encode($item) }}" data-upload-url="{{ route('panneau.upload.picture') }}"></div>
        </div>
        <div class="col-xs-8 col-sm-9">
            {!! $form !!}
            
            <div 
                data-react="DangerZone" 
                data-delete-title="{{ trans('user.deletion.title') }}"
                data-delete-description="{{ trans('user.deletion.description') }}"
                data-delete-confirmation="{{ trans('user.deletion.confirmation') }}"
                data-form="{{ json_encode($deleteForm->toArray()) }}"
            ></div>
        </div>
    </div>

@endsection
