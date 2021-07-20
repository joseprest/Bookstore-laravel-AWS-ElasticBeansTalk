<h2>{{ trans('screen.controls') }}</h2>

<div
    data-react="ScreenControls"
    data-screen="{{ json_encode($screen) }}"
    data-is-admin="{{ Auth::user()->is('admin') ? 'true':'false' }}"
    data-pings="{{ json_encode($pings) }}"
    data-commands="{{ json_encode($commands) }}"
    data-last-ping-max="{{ config('manivelle.screens.last_ping_max') }}"
></div>
