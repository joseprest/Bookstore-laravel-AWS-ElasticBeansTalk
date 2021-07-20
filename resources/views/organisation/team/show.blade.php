@extends('layouts.organisation')

@section('content_header')

    <h1 class="container">Équipe <small>/ Utilisateur #{{ $item->id }}</small></h1>

@endsection

@section('content')
    
    <div class="row">
        <div class="col-sm-6">
            <div class="snippet snippet-user">
                <div class="snippet-thumbnail">
                    <a href="#">
                        <img src="#" width="100%" />
                    </a>
                </div>
                <div class="snippet-caption">
                    @if(!empty($item->name))
                    <h4>{{ $item->name }}</h4>
                    @endif
                    <dl class="dl-horizontal">
                        <dt>Courriel</dt>
                        <dd>{{ $item->email }}</dd>
                        <dt>ID</dt>
                        <dd>{{ $item->id }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <h4>Modifier le membre de l'équipe</h4>
            {!! $form !!}
        </div>
    </div>
    
    
    
@endsection
