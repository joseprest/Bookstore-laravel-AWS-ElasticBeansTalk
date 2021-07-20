<?php

namespace Manivelle\Listeners;

use Log;
use Event;
use Manivelle\Events\Event as BaseEvent;
use Manivelle\Events\SourceSyncStarted;
use Manivelle\Events\SourceSyncStopped;
use Manivelle\Events\SourceSyncFinished;
use Manivelle\Listeners\Traits\UpdateScreenCaches;

class SourceSubscriber
{
    use UpdateScreenCaches;
    
    /**
     * Listener when a SourceSync is stopped
     * @param  Manivelle\Events\SourceSyncStopped $event The SourceSync stopped event
     * @return void
     */
    public function onSourceSyncStopped(SourceSyncStopped $event)
    {
        $model = $event->sourceSync;
        $this->updateScreenCaches($model);
    }
    
    /**
     * Listener when a SourceSync is finished
     * @param  Manivelle\Events\SourceSyncFinished $event The SourceSync finished event
     * @return void
     */
    public function onSourceSyncFinished(SourceSyncFinished $event)
    {
        $model = $event->sourceSync;
        $this->updateScreenCaches($model);
    }
    
    public function subscribe($events)
    {
        $events->listen(
            SourceSyncStopped::class,
            '\Manivelle\Listeners\SourceSubscriber@onSourceSyncStopped'
        );
        $events->listen(
            SourceSyncFinished::class,
            '\Manivelle\Listeners\SourceSubscriber@onSourceSyncFinished'
        );
    }
}
