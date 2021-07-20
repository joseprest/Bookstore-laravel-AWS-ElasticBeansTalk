@extends('panneau::layout')

@section('content')
    @include('admin.submenu')
    <h1>{{ trans('admin.importations.title') }}</h1>
    <div class="list list-rows">
        @foreach ($sources as $source)
            <div class="list-item">
                <a
                    href="{{ $source['url'] }}"
                    class="thumbnail"
                >
                    <span class="caption caption-lg">
                        {{ $source['name'] }}
                        @if ($source['isSyncing'])
                            <small>({{ trans('importation.started') }})</small>
                        @endif
                    </span>

                </a>
            </div>
        @endforeach
    </div>
@endsection
