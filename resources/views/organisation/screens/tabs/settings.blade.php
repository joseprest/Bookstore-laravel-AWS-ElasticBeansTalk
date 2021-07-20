<h2>{{ trans('screen.settings') }}</h2>

{!! $form !!}

<div 
    data-react="DangerZone" 
    data-delete-title="{{ trans('screen.deletion.title') }}"
    data-delete-description="{{ trans('screen.deletion.description') }}"
    data-delete-confirmation="{{ trans('screen.deletion.confirmation') }}"
    data-form="{{ json_encode($unlinkForm->toArray()) }}"
></div>
