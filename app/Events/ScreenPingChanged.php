<?php

namespace Manivelle\Events;

use Manivelle\Models\ScreenPing;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ScreenPingChanged extends Event implements ShouldBroadcast
{
    use SerializesModels;
    
    public $ping;
    
    public function __construct(ScreenPing $ping)
    {
        $this->ping = $ping;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['screen.'.$this->ping->screen->id];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'ping.changed';
    }
    
    /**
     * Get the queue for broadcasting
     *
     * @return string
     */
    public function onQueue()
    {
        return config('queue.connections.priority.queue');
    }
}
