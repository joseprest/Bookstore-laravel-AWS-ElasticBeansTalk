@extends('panneau::layout')

@section('content')
    <div class="content-header">
        <h1>
            <a href="{{ Localizer::route('admin.importations.index') }}">
                {{ trans('admin.importations.title') }}
            </a>
            <small>
                / <a href="{{ $sourceURL }}">{{ $source->name }}</a>
                / {{ trans('importation.libraryList.title') }}
            </small>
        </h1>
    </div>
    <form method="POST" action="{{ $savePOSTURL }}">
        {{ csrf_field() }}
        @foreach($allLibraries as $library)
        <?php $libraryChecked = array_key_exists($library['key'], $selectedLibraries) ?>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="libraries[{{ $library['key'] }}]" {{ $libraryChecked ? 'checked="checked"' : '' }}> {{ $library['title'] }}
            </label>
        </div>
        @endforeach
        <button type="submit" class="btn btn-primary">{{ trans('general.actions.save') }}</button>
        <a href="{{ $sourceURL }}" class="btn btn-default">{{ trans('general.actions.cancel') }}</a>
    </form>
@endsection
