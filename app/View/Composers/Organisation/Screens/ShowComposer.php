<?php namespace Manivelle\View\Composers\Organisation\Screens;

use View;
use Manivelle\User;
use Panneau;
use Route;
use Auth;
use Localizer;

use Illuminate\Http\Request;
use Manivelle\Models\ScreenPivot;

class ShowComposer
{
    protected $request;
    protected $organisation;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->organisation = $request->organisation;
    }
    
    public function compose($view)
    {
        $organisation = $this->organisation;
        $screen = $view->item;
        $channels = $screen->channels;
        $currentChannel = $view->channel;
        $user = Auth::user();
        
        /**
         * Tabs
         */
        $route = Route::current();
        $routeName = $route ? $route->getName():'';
        preg_match('/^organisation\.screens\.([^\.]+)\.[^\.]+$/', $routeName, $matches);
        $tabSelected = isset($matches[1]) && $matches[1] !== 'show' ? $matches[1]:'slideshow';
        
        
        $tabs = [];
        
        $tabs['slideshow'] = array(
            'active' => $tabSelected === 'slideshow',
            'label' => trans('screen.tabs.slideshow'),
            'url' => route(Localizer::routeName('organisation.screens.slideshow'), array(
                $organisation->slug,
                $screen->id
            )),
            'view' => 'organisation.screens.tabs.slideshow'
        );
        
        $tabs['channels'] = array(
            'active' => $tabSelected === 'channels',
            'label' => trans('screen.tabs.channels'),
            'url' => route(Localizer::routeName('organisation.screens.channels'), array(
                $organisation->slug,
                $screen->id
            )),
            'view' => 'organisation.screens.tabs.channels'
        );
        
        $tabs['stats'] = array(
            'active' => $tabSelected === 'stats',
            'label' => trans('screen.tabs.stats'),
            'url' => route(Localizer::routeName('organisation.screens.stats'), array(
                $organisation->slug,
                $screen->id
            )),
            'view' => 'organisation.screens.tabs.stats'
        );
        
        if ($user->can('screenViewControls', $organisation)) {
            $tabs['controls'] = array(
                'active' => $tabSelected === 'controls',
                'label' => trans('screen.tabs.controls'),
                'url' => route(Localizer::routeName('organisation.screens.controls'), array(
                    $organisation->slug,
                    $screen->id
                )),
                'view' => 'organisation.screens.tabs.controls'
            );
        }
        
        if ($user->can('screenViewSettings', $organisation)) {
            $tabs['settings'] = array(
                'active' => $tabSelected === 'settings',
                'label' => trans('screen.tabs.settings'),
                'url' => route(Localizer::routeName('organisation.screens.settings'), array(
                    $organisation->slug,
                    $screen->id
                )),
                'view' => 'organisation.screens.tabs.settings'
            );
        }
        
        $view->tabSelected = $tabSelected;
        $view->tabs = $tabs;
    }
}
