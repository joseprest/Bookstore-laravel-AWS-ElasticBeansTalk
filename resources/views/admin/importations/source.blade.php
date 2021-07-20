@extends('panneau::layout')

@section('content')
    @include('admin.submenu')
    <div class="content-header">
        <h1>
            <a href="{{ Localizer::route('admin.importations.index') }}">
                {{ trans('admin.importations.title') }}
            </a>
            <small>/ {{ $source->name }}</small>
        </h1>
    </div>
    <div>
        <hr>
        <h3>{{ trans('importation.last.title') }}</h3>
        @if (!count($lastSyncs))
            <p>{{ trans('importation.last.empty') }}</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ trans('importation.last.status') }}</th>
                        <th>{{ trans('importation.last.start') }}</th>
                        <th>{{ trans('importation.last.end') }}</th>
                        <th>{{ trans('importation.last.duration') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lastSyncs as $sync)
                        <tr class="{{ $sync['status'] === 'started' ? 'success' : ($sync['status'] === 'stopped' ? 'warning' : '') }}">
                            <td>{{ $sync['statusString'] }}</td>
                            <td>{{ $sync['startedAt'] }}</td>
                            <td>{{ $sync['finishedAt']}}</td>
                            <td>{{ $sync['duration']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if ($hasLibraryList)
            @include('admin.importations.libraries')
        @endif
    </div>
@endsection
