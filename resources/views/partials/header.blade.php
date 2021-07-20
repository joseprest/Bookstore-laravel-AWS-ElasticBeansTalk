<nav class="navbar navbar-primary navbar-fixed-top">
    <div data-react="BackgroundTasks"></div>
    <div class="navbar-inner">
        
        <div class="navbar-col navbar-title">
            
            @if(isset($currentOrganisation))
                <a class="navbar-brand" href="{{ route(Localizer::routeName('organisation.home'), array($currentOrganisation->slug)) }}">
                    <span class="logo"></span>
                </a>
            @else
                <a class="navbar-brand" href="{{ Localizer::route('home') }}">
                    <span class="logo"></span>
                </a>
            @endif
            
            @if(isset($organisations))
            <div class="btn-container">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(isset($currentOrganisation))
                    <span class="navbar-screen-name">{{ $currentOrganisation->name }}</span>
                    @endif
                    <span class="glyphicon glyphicon-menu-down"></span>
                </button>
                
                <ul class="dropdown-menu">
                    
                    @if(isset($currentOrganisation))
                        <li><a href="{{ route(Localizer::routeName('organisation.home'), [$currentOrganisation->slug]) }}">
                            <span>{{ trans('layout.nav.organisation.see_screens') }}</span>
                        </a></li>
                        
                        @can('edit', $currentOrganisation)
                        <li><a href="{{ route(Localizer::routeName('organisation.edit'), [$currentOrganisation->slug]) }}">
                            <span>{{ trans('layout.nav.organisation.modify') }}</span>
                        </a></li>
                        @endcan
                        
                        @if(sizeof($organisations))
                        <li role="separator" class="divider"></li>
                        @endif
                    @endif
                    
                    @if(sizeof($organisations))
                    <li class="dropdown-header">{{ trans('layout.nav.organisations.title') }}</li>
                    @foreach($organisations as $organisation)
                        <li><a href="{{ route(Localizer::routeName('organisation.home'), [$organisation->slug]) }}">
                            <span>{{ $organisation->name }}</span>
                        </a></li>
                    @endforeach
                    @endif
                </ul>
            </div>
            @endif
            
        </div>
        
        <div class="navbar-col navbar-center">
            
            @if(isset($screens) && isset($currentScreen))
            <div class="navbar-screen">
                
                <div class="btn-container">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-phone"></span>
                        <span class="navbar-screen-name">{{ $currentScreen->name }}</span>
                        <span class="glyphicon glyphicon-menu-down"></span>
                    </button>
                    
                    <ul class="dropdown-menu">
                        <li class="dropdown-header">{{ trans('layout.nav.screens.title') }}</li>
                        @foreach($screens as $screen)
                            <li><a href="{{ route(Localizer::routeName('organisation.screens.show'), [$currentOrganisation->slug, $screen->id]) }}">
                                <span class="glyphicon glyphicon-phone"></span>
                                <span>{{ $screen->name }}</span>
                            </a></li>
                        @endforeach
                    </ul>
                </div>
                
            </div>
            @endif
            
        </div>
        
            
        @if(Auth::check())
            <div class="navbar-col navbar-profile">
                @include('partials.header_user')
            </div>
        @else
            <div class="navbar-col navbar-locale">
                @include('partials.header_locale')
            </div>
        @endif
            
        
    </div>
    
</nav>
