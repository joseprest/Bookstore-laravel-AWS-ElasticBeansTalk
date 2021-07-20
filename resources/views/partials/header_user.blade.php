<div class="navbar-user">
    <div class="btn-container">
        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ !empty($user->name) ? $user->name:$user->email }} <span class="glyphicon glyphicon-menu-down"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="{{ Localizer::route('account') }}">{{ trans('layout.nav.user.my_account') }}</a></li>
            @role('admin')
                <li><a href="{{ Localizer::route('admin') }}">{{ trans('layout.nav.user.admin') }}</a></li>
            @endrole
            <li role="separator" class="divider"></li>
            <li><a href="{{ route('panneau.auth.logout') }}">{{ trans('layout.nav.user.logout') }}</a></li>
        </ul>
    </div>
</div>
