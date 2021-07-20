<?php namespace Manivelle\View\Composers;

use Manivelle;
use Cache;
use Auth;
use Illuminate\Http\Request;
use Manivelle\Models\Organisation;

class HeaderComposer
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function compose($view)
    {
        if ($view->currentOrganisation && $view->currentScreen) {
            $organisation = $view->currentOrganisation;
            $cacheKey = $this->getCacheKey('organisation_'.$organisation->id.'_screens');
            $view->screens = Cache::remember($cacheKey, 60, function () use ($organisation) {
                $organisation->loadScreens();
                return $organisation->screens;
            });
        }
        
        $view->organisations = null;
        if (Auth::check()) {
            $user = Auth::user();
            $current = $view->currentOrganisation;
            $view->organisations = $user->loadOrganisations()
                ->organisations
                ->map(function ($item) {
                    return $item->organisation;
                })
                ->sortBy('name')
                ->values()
                ->filter(function ($item) use ($current) {
                    return !$current || $item->id !== $current->id;
                });
        }
    }
    
    protected function getCacheKey($key)
    {
        return 'header_'.$key;
    }
    
    public function clearUserCache($item = null)
    {
        $items = $item ? [$item]:User::all();
        foreach ($items as $item) {
            Cache::forget($this->getCacheKey('user_'.$item->id.'_organisations'));
        }
    }
    
    public function clearOrganisationCache($item = null)
    {
        $items = $item ? [$item]:Organisation::all();
        foreach ($items as $item) {
            Cache::forget($this->getCacheKey('organisation_'.$item->id.'_screens'));
        }
    }
}
