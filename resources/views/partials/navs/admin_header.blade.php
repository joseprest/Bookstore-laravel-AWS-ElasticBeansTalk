<ul class="nav navbar-nav">
    <li class="{{ $activeItem === 'admin' ? 'active':'' }}">
        <a href="{{ Localizer::route('admin') }}">Administration</a>
    </li>
    <li class="{{ $activeItem === 'users' ? 'active':'' }}">
        <a href="{{ Localizer::route('admin.users.index') }}">Utilisateurs</a>
    </li>
    <li class="{{ $activeItem === 'organisations' ? 'active':'' }}">
        <a href="{{ Localizer::route('admin.organisations.index') }}">Organisations</a>
    </li>
    <li class="{{ $activeItem === 'settings' ? 'active':'' }}">
        <a href="#">Settings</a>
    </li>
</ul>
