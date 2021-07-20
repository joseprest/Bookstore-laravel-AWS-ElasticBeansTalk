@if($hasLocales)
    <div class="navbar-locale">
        <div class="btn-container">
            <button type="button"
                class="btn dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                {{$currentLocaleName}} <span class="glyphicon glyphicon-menu-down"></span>
            </button>
            <ul class="dropdown-menu">
                @foreach($locales as $locale)
                    @if(!$locale['current'])
                        <li><a href="{{$locale['url']}}">{{$locale['name']}}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
@endif
