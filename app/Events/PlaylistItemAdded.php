<?php

namespace Manivelle\Events;

use Manivelle\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Manivelle\Models\PlaylistItem;
use Manivelle\Models\Playlist;

class PlaylistItemAdded extends Event
{
    use SerializesModels;
    
    public $item;
    public $playlist;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Playlist $playlist, PlaylistItem $item)
    {
        $this->item = $item;
        $this->playlist = $playlist;
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
