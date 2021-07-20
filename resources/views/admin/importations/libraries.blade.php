<hr>
<h3>{{ trans('importation.library.title') }}</h3>

@if(!count($libraries['selectedLibraries']))
<p>{{ trans('importation.libraryList.empty') }}</p>
@else
    <ul>
    @foreach($libraries['selectedLibraries'] as $library)
        <li>
            {{ $library['title'] }} -
            {{ trans('importation.libraryList.nb_items', ['nb' => $library['count']]) }}</li>
    @endforeach
    </ul>
@endif

<p>
    <a href="{{ $libraries['editLink'] }}" class="btn btn-default">{{ trans('importation.libraryList.actions.editList') }}</a>
</p>
