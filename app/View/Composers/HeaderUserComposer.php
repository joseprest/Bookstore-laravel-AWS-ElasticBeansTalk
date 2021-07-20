<?php namespace Manivelle\View\Composers;

use Auth;
use Manivelle;
use Image;
use Cache;
use Manivelle\User;

class HeaderUserComposer
{
    public function compose($view)
    {
        $view->user = $user = Auth::check() ? Auth::user():null;
        
        $cacheKey = $this->getCacheKey('user_'.$user->id.'_organisation');
        $view->organisation = Cache::remember($cacheKey, 60, function () use ($user) {
            $user->loadOrganisation();
            return $user->organisation;
        });
        
        $cacheKey = $this->getCacheKey('user_'.$user->id.'_avatar');
        $view->avatar = Cache::remember($cacheKey, 60, function () use ($user) {
            $user->loadPictures();
            return $user->avatar ? Image::url($user->avatar->link, ['avatar_small']):asset('/img/avatar.png');
        });
    }
    
    public function clearUserCache($item = null)
    {
        $items = $item ? [$item]:User::all();
        foreach ($items as $item) {
            Cache::forget($this->getCacheKey('user_'.$item->id.'_organisation'));
            Cache::forget($this->getCacheKey('user_'.$item->id.'_avatar'));
        }
    }
    
    protected function getCacheKey($key)
    {
        return 'header_'.$key;
    }
}
