<?php

namespace Manivelle\Events;

use Manivelle\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Manivelle\Models\SourceSync;

class SourceSyncStarted extends Event
{
    use SerializesModels;
    
    public $sourceSync;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SourceSync $sourceSync)
    {
        $this->sourceSync = $sourceSync;
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
