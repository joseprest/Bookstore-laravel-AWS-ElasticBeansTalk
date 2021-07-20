<?php

namespace Manivelle\Listeners;

use Log;
use Event;

use Manivelle\Events\Event as BaseEvent;
use Manivelle\Events\BubbleChanged;
use Manivelle\Events\BubbleCreated;
use Manivelle\Events\BubbleDeleted;
use Manivelle\Events\BubbleManuallyCreated;
use Manivelle\Events\ScreenChanged;
use Manivelle\Jobs\CreateCache;
use Manivelle\Jobs\ClearCache;
use Manivelle\Listeners\Traits\UpdateScreenCaches;

class BubbleSubscriber
{
    use UpdateScreenCaches;
    
    /**
     * Listener when a Bubble is created
     * @param  Manivelle\Events\BubbleCreated $event The bubble created event
     * @return void
     */
    public function onBubbleCreated(BubbleCreated $event)
    {
        $model = $event->bubble;
        
        //$this->updateBubblesPageCache($model);
        $this->updateBubbleSuggestionsCache($model);
    }
    
    /**
     * Listener when a Bubble is manually created
     * @param  Manivelle\Events\BubbleManuallyCreated $event The bubble created event
     * @return void
     */
    public function onBubbleManuallyCreated(BubbleManuallyCreated $event)
    {
        $model = $event->bubble;
        $this->updateScreenCaches($model);
    }
    
    /**
     * Listener when a Bubble changed
     * @param  Manivelle\Events\BubbleChanged $event The bubble changed event
     * @return void
     */
    public function onBubbleChanged(BubbleChanged $event)
    {
        $model = $event->bubble;
        
        //$this->updateBubblesPageCache($model);
        $this->updateBubbleSuggestionsCache($model);
        $model->clearCaches();
    }
    
    /**
     * Listener when a Bubble is deleted
     * @param  Manivelle\Events\BubbleDeleted $event The bubble deleted event
     * @return void
     */
    public function onBubbleDeleted(BubbleDeleted $event)
    {
        $model = $event->bubble;
        
        //$this->updateBubblesPageCache($model);
        $this->clearBubbleSuggestionsCache($model);
    }
    
    
    protected function updateBubblesPageCache($model)
    {
        $cache = \Manivelle\Models\Bubble::class.'\\page_json';
        dispatch(new CreateCache($cache, $model, true));
    }
    
    protected function updateBubbleSuggestionsCache($model)
    {
        $cache = \Manivelle\Models\Bubble::class.'\\suggestions';
        dispatch(new CreateCache($cache, $model, true));
    }
    
    protected function clearBubbleSuggestionsCache($model)
    {
        $cache = \Manivelle\Models\Bubble::class.'\\suggestions';
        dispatch(new ClearCache($cache, $model));
    }
    
    public function subscribe($events)
    {
        $events->listen(BubbleManuallyCreated::class, '\Manivelle\Listeners\BubbleSubscriber@onBubbleManuallyCreated');
        $events->listen(BubbleChanged::class, '\Manivelle\Listeners\BubbleSubscriber@onBubbleChanged');
        $events->listen(BubbleCreated::class, '\Manivelle\Listeners\BubbleSubscriber@onBubbleCreated');
        $events->listen(BubbleDeleted::class, '\Manivelle\Listeners\BubbleSubscriber@onBubbleDeleted');
    }
}
