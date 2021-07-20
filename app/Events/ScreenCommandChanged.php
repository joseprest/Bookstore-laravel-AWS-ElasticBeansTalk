<?php

namespace Manivelle\Events;

use Manivelle\Models\ScreenCommand;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ScreenCommandChanged extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $command;

    public function __construct(ScreenCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['screen.'.$this->command->screen->id];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'command.changed';
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
