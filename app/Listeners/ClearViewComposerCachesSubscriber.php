<?php

namespace Manivelle\Listeners;

use Closure;

use Manivelle\Events\UserChanged;
use Manivelle\Events\OrganisationChanged;
use Manivelle\Events\OrganisationUserChanged;
use Manivelle\Events\ScreenChanged;

use Manivelle\User;
use Manivelle\Models\Organisation;
use Manivelle\Models\Screen;

class ClearViewComposerCachesSubscriber
{
    public function onUserChanged(UserChanged $event)
    {
        $this->clearUserCaches($event->user);
    }
    
    public function onOrganisationUserChanged(OrganisationUserChanged $event)
    {
        if ($event->user->user) {
            $this->clearUserCaches($event->user->user);
        }
    }
    
    public function onOrganisationChanged(OrganisationChanged $event)
    {
        $this->clearOrganisationCaches($event->organisation);
        
        $event->organisation->load('users');
        foreach ($event->organisation->users as $user) {
            $this->clearUserCaches($user->user);
        }
    }
    
    public function onScreenChanged(ScreenChanged $event)
    {
        $this->clearScreenCaches($event->screen);
        
        foreach ($event->screen->organisations as $organisation) {
            $this->clearOrganisationCaches($organisation);
        }
    }
    
    public function subscribe($events)
    {
        $events->listen(
            \Manivelle\Events\UserChanged::class,
            '\Manivelle\Listeners\ClearViewComposerCachesSubscriber@onUserChanged'
        );
        
        $events->listen(
            \Manivelle\Events\OrganisationUserChanged::class,
            '\Manivelle\Listeners\ClearViewComposerCachesSubscriber@onOrganisationUserChanged'
        );
        
        $events->listen(
            \Manivelle\Events\OrganisationChanged::class,
            '\Manivelle\Listeners\ClearViewComposerCachesSubscriber@onOrganisationChanged'
        );
        
        $events->listen(
            \Manivelle\Events\ScreenChanged::class,
            '\Manivelle\Listeners\ClearViewComposerCachesSubscriber@onScreenChanged'
        );
    }

    protected function clearUserCaches(User $user)
    {
        $this->eachComposers([
            \Manivelle\View\Composers\HeaderUserComposer::class,
            \Manivelle\View\Composers\HeaderComposer::class
        ], function ($composer) use ($user) {
            $composer->clearUserCache($user);
        });
    }
    
    protected function clearOrganisationCaches(Organisation $organisation)
    {
        $this->eachComposers([
            \Manivelle\View\Composers\HeaderComposer::class
        ], function ($composer) use ($organisation) {
            $composer->clearOrganisationCache($organisation);
        });
    }
    
    protected function clearScreenCaches(Screen $screen)
    {
    }
    
    public function eachComposers($composers, $fnc)
    {
        foreach ($composers as $composer) {
            $composer = app($composer);
            $fnc($composer);
        }
    }
}
