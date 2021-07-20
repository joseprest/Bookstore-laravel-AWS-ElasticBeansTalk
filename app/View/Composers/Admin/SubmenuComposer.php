<?php namespace Manivelle\View\Composers\Admin;

use Auth;
use Manivelle\User;
use Panneau;
use Route;
use Localizer;

class SubmenuComposer
{
    
    public function compose($view)
    {
        /**
         * Tabs
         */
        $route = Route::current();
        $routeName = $route ? $route->getName():'';
        preg_match('/^admin\.([^\.]+)(\.[^\.]+)?\.[^\.]+$/', $routeName, $matches);
        $tabSelected = isset($matches[1]) && $matches[1] !== 'show' ? $matches[1]:'slideshow';
        $tabsKey = array('importations', 'organisations', 'users');
        $tabs = array();
        foreach ($tabsKey as $tabKey) {
            $tabs[$tabKey] = array(
                'active' => $tabSelected === $tabKey,
                'label' => trans('admin.tabs.'.$tabKey),
                'url' => Localizer::route($tabKey === 'admin' ? 'admin':('admin.'.$tabKey.'.index'))
            );
        }
        $view->tabSelected = $tabSelected;
        $view->tabs = $tabs;
    }
}
