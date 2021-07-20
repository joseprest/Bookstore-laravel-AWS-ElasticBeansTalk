<?php

namespace Manivelle\Listeners;

use Log;
use Event;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Manivelle\Models\Screen;
use Manivelle\Models\ScreenCache;

use Manivelle\Events\ScreenChanged;
use Manivelle\Events\ScreenLinked;
use Manivelle\Events\ScreenChannelChanged;
use Manivelle\Events\ScreenChannelAdded;
use Manivelle\Events\ScreenChannelRemoved;
use Manivelle\Events\CacheCreated;
use Manivelle\Events\ScreenCacheCreated;
use Manivelle\Events\ScreenCachesCreated;
use Manivelle\Events\ScreenPlaylistDetached;
use Manivelle\Events\ScreenPlaylistAttached;

use Manivelle\Jobs\SendScreenNotification;
use Manivelle\Jobs\CreateScreenCaches;
use Manivelle\Listeners\Traits\UpdateScreenCaches;

class ScreenSubscriber
{
    use DispatchesJobs, UpdateScreenCaches;

    /**
     * Listener when a Screen changed
     * @param  Manivelle\Events\ScreenChanged $event The Screen changed event
     * @return void
     */
    public function onScreenChanged(ScreenChanged $event)
    {
        $model = $event->screen;

        dispatch(new SendScreenNotification($model, 'screen:update'));
        $this->updateScreenCaches($model);
    }

    /**
     * Listener when a Screen is linked to an Organisation
     * @param  Manivelle\Events\ScreenLinked $event The Screen linked event
     * @return void
     */
    public function onScreenLinked(ScreenLinked $event)
    {
        $model = $event->screen;

        dispatch(new SendScreenNotification($model, 'screen:update'));
        $this->updateScreenCaches($model);
    }

    /**
     * Listener when a Playlist is attached to a Screen
     * @param  Manivelle\Events\ScreenPlaylistAttached $event The Screen playlist attached event
     * @return void
     */
    public function onScreenPlaylistAttached(ScreenPlaylistAttached $event)
    {
        $model = $event->screen;
        $this->updateScreenCaches($model);
    }

    /**
     * Listener when a Playlist is detached from a Screen
     * @param  Manivelle\Events\ScreenPlaylistDetached $event The Screen playlist attached event
     * @return void
     */
    public function onScreenPlaylistDetached(ScreenPlaylistDetached $event)
    {
        $model = $event->screen;
        $this->updateScreenCaches($model);
    }

    /**
     * Listener when a ScreenChannel changed
     * @param  Manivelle\Events\ScreenChannelChanged $event The ScreenChannel changed event
     * @return void
     */
    public function onScreenChannelChanged(ScreenChannelChanged $event)
    {
        $model = $event->channel;
        $this->updateScreenCaches($model);
    }

    /**
     * Listener when a ScreenChannel added
     * @param  Manivelle\Events\ScreenChannelAdded $event The ScreenChannel added event
     * @return void
     */
    public function onScreenChannelAdded(ScreenChannelAdded $event)
    {
        $model = $event->screen;
        $this->updateScreenCaches($model);
    }

    /**
     * Listener when a ScreenChannel removed
     * @param  Manivelle\Events\ScreenChannelRemoved $event The ScreenChannel removed event
     * @return void
     */
    public function onScreenChannelRemoved(ScreenChannelRemoved $event)
    {
        $model = $event->screen;
        $this->updateScreenCaches($model);
    }

    /**
     * Listener when a Cache is created
     * @param  Manivelle\Events\CacheCreated $event The cache created event
     * @return void
     */
    public function onCacheCreated(CacheCreated $event)
    {
        if ($event->model instanceof Screen) {
            Event::fire(new ScreenCacheCreated($event->model, $event->cache));
        }
    }

    /**
     * Listener when a Screen cache is created
     * @param  Manivelle\Events\ScreenCacheCreated $event The Screen cache created event
     * @return void
     */
    public function onScreenCacheCreated(ScreenCacheCreated $event)
    {
        $model = $event->screen;
        $cacheName = $event->cache;

        //Get next version
        $nextVersion = $model->getNextCacheVersion($cacheName);

        //Create screen cache
        $screenCache = new ScreenCache();
        $screenCache->name = $cacheName;
        $screenCache->version = $nextVersion;
        $model->caches()->save($screenCache);

        if ($model->hasAllCachesForVersion($nextVersion)) {
            Event::fire(new ScreenCachesCreated($model));
        }
    }

    /**
     * Listener when all Screen caches are created
     * @param  Manivelle\Events\ScreenCachesCreated $event The Screen caches created event
     * @return void
     */
    public function onScreenCachesCreated(ScreenCachesCreated $event)
    {
        $model = $event->screen;

        dispatch(new SendScreenNotification($model, 'data:update'));
    }

    public function subscribe($events)
    {
        $events->listen(
            ScreenChanged::class,
            '\Manivelle\Listeners\ScreenSubscriber@onScreenChanged'
        );

        $events->listen(
            ScreenLinked::class,
            '\Manivelle\Listeners\ScreenSubscriber@onScreenLinked'
        );

        $events->listen(
            ScreenPlaylistAttached::class,
            '\Manivelle\Listeners\ScreenSubscriber@onScreenPlaylistAttached'
        );

        $events->listen(
            ScreenPlaylistDetached::class,
            '\Manivelle\Listeners\ScreenSubscriber@onScreenPlaylistDetached'
        );

        $events->listen(
            ScreenChannelChanged::class,
            '\Manivelle\Listeners\ScreenSubscriber@onScreenChannelChanged'
        );

        $events->listen(
            ScreenChannelAdded::class,
            '\Manivelle\Listeners\ScreenSubscriber@onScreenChannelAdded'
        );

        $events->listen(
            ScreenChannelRemoved::class,
            '\Manivelle\Listeners\ScreenSubscriber@onScreenChannelRemoved'
        );

        $events->listen(
            CacheCreated::class,
            '\Manivelle\Listeners\ScreenSubscriber@onCacheCreated'
        );

        $events->listen(
            ScreenCacheCreated::class,
            '\Manivelle\Listeners\ScreenSubscriber@onScreenCacheCreated'
        );

        $events->listen(
            ScreenCachesCreated::class,
            '\Manivelle\Listeners\ScreenSubscriber@onScreenCachesCreated'
        );
    }
}
