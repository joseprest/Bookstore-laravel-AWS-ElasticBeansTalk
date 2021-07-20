<?php

namespace Manivelle\Listeners;

use Log;
use Event;
use Manivelle\Events\Event as BaseEvent;

use Manivelle\Events\PlaylistItemAdded;
use Manivelle\Events\PlaylistItemRemoved;
use Manivelle\Events\PlaylistItemsOrderChanged;
use Manivelle\Events\PlaylistDeleting;
use Manivelle\Listeners\Traits\UpdateScreenCaches;

class PlaylistSubscriber
{
    use UpdateScreenCaches;
    
    /**
     * Listener when a PlaylistItem is added
     * @param  Manivelle\Events\PlaylistItemAdded $event The PlaylistItem added event
     * @return void
     */
    public function onPlaylistItemAdded(PlaylistItemAdded $event)
    {
        $model = $event->playlist;
        
        $this->updateScreenCaches($model);
    }
    
    /**
     * Listener when a PlaylistItem is removed
     * @param  Manivelle\Events\PlaylistItemRemoved $event The PlaylistItem removed event
     * @return void
     */
    public function onPlaylistItemRemoved(PlaylistItemRemoved $event)
    {
        $model = $event->playlist;
        
        $this->updateScreenCaches($model);
    }
    
    /**
     * Listener when Playlist items order changed
     * @param  Manivelle\Events\PlaylistItemsOrderChanged $event The Playlist items order changed event
     * @return void
     */
    public function onPlaylistItemsOrderChanged(PlaylistItemsOrderChanged $event)
    {
        $model = $event->playlist;
        
        $this->updateScreenCaches($model);
    }
    
    /**
     * Listener when a Playlist is deleting
     * @param  Manivelle\Events\PlaylistDeleting $event The Playlist deleting event
     * @return void
     */
    public function onPlaylistDeleting(PlaylistDeleting $event)
    {
        $model = $event->playlist;
        
        if ($model->condition) {
            $model->condition->delete();
        }
        
        foreach ($model->screens as $screen) {
            $model->screens()->detach($screen);
        }
        
        foreach ($model->items as $item) {
            $item->delete();
        }
    }
    
    public function subscribe($events)
    {
        $events->listen(
            PlaylistItemAdded::class,
            '\Manivelle\Listeners\PlaylistSubscriber@onPlaylistItemAdded'
        );
        $events->listen(
            PlaylistItemRemoved::class,
            '\Manivelle\Listeners\PlaylistSubscriber@onPlaylistItemRemoved'
        );
        $events->listen(
            PlaylistItemsOrderChanged::class,
            '\Manivelle\Listeners\PlaylistSubscriber@onPlaylistItemsOrderChanged'
        );
        $events->listen(
            PlaylistDeleting::class,
            '\Manivelle\Listeners\PlaylistSubscriber@onPlaylistDeleting'
        );
    }
}
