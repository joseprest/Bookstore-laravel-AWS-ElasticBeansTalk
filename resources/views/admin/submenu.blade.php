<ul class="nav nav-submenu" role="tablist">
    @foreach($tabs as $key => $tab)
    <li class="{{ $tab['active'] ? 'active':''}}">
        <a href="{{ $tab['url'] }}" aria-controls="{{ $key }}" role="tab">{{ $tab['label'] }}</a>
    </li>
    @endforeach
</ul>
