@extends('layouts.organisation')

@section('content_header')

    <div class="container container-cols">
        <div class="container-col">
            <h1>
                <a href="{{ route(Localizer::routeName('organisation.home'), [$currentOrganisation->slug]) }}">{{ trans('screen.screens')}}</a>
                <small>/ {{ $item->name }}</small>
            </h1>
        </div>
        <div class="container-col right">
            <a href="{{ route('screen.home', [$item->uuid]) }}" target="_blank" class="btn btn-default">{{ trans('screen.view_live') }}</a>
        </div>
    </div>

    @if(!$item->online)
        <!--<div class="container-alerts">
            <div class="container">
                <div class="alert-offline">
                    <span class="glyphicon glyphicon-exclamation-sign"></span>
                    <span>{{ trans('screen.currently_offline') }}</span>
                </div>
            </div>
        </div>-->
    @endif

@endsection

@section('content')

    <div class="container-tab">
        <ul class="nav nav-submenu" role="tablist">
            @foreach($tabs as $key => $tab)
            <li class="{{ $tab['active'] ? 'active':''}}">
                <a href="{{ $tab['url'] }}" aria-controls="{{ $key }}" role="tab">{{ $tab['label'] }}</a>
            </li>
            @endforeach
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active">
                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        @if($tabSelected && isset($tabs[$tabSelected]))
                            @include($tabs[$tabSelected]['view'])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
