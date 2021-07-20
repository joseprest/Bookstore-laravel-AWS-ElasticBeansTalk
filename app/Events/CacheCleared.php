<?php

namespace Manivelle\Events;

use Manivelle\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CacheCleared extends Event
{
    use SerializesModels;

    public $cache;
    public $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cache, $model)
    {
        $this->cache = $cache;
        $this->model = $model;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
